<?php
namespace App\Exports;

use App\Models\Rent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Rents implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Rent::query()->with('images'); // Rasmlarni yuklaymiz

        // Search filter
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Title',
            'Description',
            'Price',
            'Location',
            'Status',
            'Images Count',
        ];
    }

    public function map($rent): array
    {
        static $index = 0;
        $index++;

        // Birinchi rasmni olish
        $firstImage = $rent->images->first();
        $firstImagePath = $firstImage ? $firstImage->image_path : '';

        return [
            $index,
            $rent->title,
            $rent->description,
            $rent->price . ' UZS',
            $rent->location,
            ucfirst($rent->status),
            $rent->images->count(), 
  
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // #
            'B' => 20,  // Title
            'C' => 30,  // Description
            'D' => 15,  // Price
            'E' => 25,  // Location
            'F' => 12,  // Status
            'G' => 5,  // Images Count
        ];
    }
}