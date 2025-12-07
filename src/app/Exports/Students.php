<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Students implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::query()->where('role', 'student');
        
        $hasJoin = false;

        // Search filter
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.phone', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function($q2) use ($search) {
                      $q2->where('faculty', 'like', "%{$search}%")
                         ->orWhere('tutor', 'like', "%{$search}%")
                         ->orWhere('rent_address', 'like', "%{$search}%")
                         ->orWhere('gender', 'like', "%{$search}%")
                         ->orWhere('group_name', 'like', "%{$search}%")
                         ->orWhere('course', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by course
        if (!empty($this->filters['sort_by']) && $this->filters['sort_by'] === 'course') {
            $order = $this->filters['sort_order'] ?? 'asc';
            
            if (!$hasJoin) {
                $query->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
                      ->select(
                          'users.*',
                          'student_profiles.faculty as profile_faculty',
                          'student_profiles.course as profile_course',
                          'student_profiles.group_name as profile_group_name',
                          'student_profiles.tutor as profile_tutor',
                          'student_profiles.rent_address as profile_rent_address',
                          'student_profiles.rent_map_url as profile_rent_map_url',
                          'student_profiles.gender as profile_gender'
                      );
                $hasJoin = true;
            }
            
            $query->orderBy('student_profiles.course', $order);
        }

        // Sort by tutor
        if (!empty($this->filters['sort_by']) && $this->filters['sort_by'] === 'tutor') {
            $order = $this->filters['sort_order'] ?? 'asc';
            
            if (!$hasJoin) {
                $query->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
                      ->select(
                          'users.*',
                          'student_profiles.faculty as profile_faculty',
                          'student_profiles.course as profile_course',
                          'student_profiles.group_name as profile_group_name',
                          'student_profiles.tutor as profile_tutor',
                          'student_profiles.rent_address as profile_rent_address',
                          'student_profiles.rent_map_url as profile_rent_map_url',
                          'student_profiles.gender as profile_gender'
                      );
                $hasJoin = true;
            }
            
            $query->orderBy('student_profiles.tutor', $order);
        }

        // Agar join qilinmagan bo'lsa, relation yuklaymiz
        if (!$hasJoin) {
            $query->with('studentProfile');
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Phone',
            'Faculty',
            'Course',
            'Group',
            'Tutor',
            'Rent Address',
            'Gender'
        ];
    }

    public function map($user): array
    {
        static $index = 0;
        $index++;

        // Join qilingan bo'lsa profile_ prefix bilan, aks holda relation orqali
        return [
            $index,
            $user->name,
            $user->email,
            $user->phone,
            $user->profile_faculty ?? $user->studentProfile->faculty ?? '',
            $user->profile_course ?? $user->studentProfile->course ?? '',
            $user->profile_group_name ?? $user->studentProfile->group_name ?? '',
            $user->profile_tutor ?? $user->studentProfile->tutor ?? '',
            $user->profile_rent_address ?? $user->studentProfile->rent_address ?? '',
            $user->profile_gender ?? $user->studentProfile->gender ?? '',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // #
            'B' => 25,  // Name
            'C' => 30,  // Email
            'D' => 15,  // Phone
            'E' => 30,  // Faculty
            'F' => 12,  // Course
            'G' => 12,  // Group
            'H' => 25,  // Tutor
            'I' => 40,  // Rent Address
            'J' => 10,  // Gender
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Header qatori (1-qator)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}