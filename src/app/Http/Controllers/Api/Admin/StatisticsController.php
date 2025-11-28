<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(): JsonResponse
{
    try {
        $students = User::where('role', 'student')->count();
        $renters = User::where('role', 'renter')->count();
        $admins = User::where('role', 'admin')->count();

        return response()->json([
            'total_students' => $students,
            'total_renters' => $renters,
            'total_admins' => $admins,
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage(),
        ], 500);
    }
}

 public function filterStudents(Request $request): JsonResponse
{
    try {
        $query = User::with('studentProfile')
            ->where('role', 'student'); 
        
        if ($request->filled('gender')) {
            $query->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }
        
        if ($request->filled('course')) {
            $query->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('course', $request->course);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function($q2) use ($search) {
                      $q2->where('faculty', 'like', "%{$search}%")
                         ->orWhere('tutor', 'like', "%{$search}%")
                         ->orWhere('rent_address', 'like', "%{$search}%")
                         ->orWhere('gender', 'like', "%{$search}%")
                         ->orWhere('group_name', 'like', "%{$search}%");
                  });
            });
        }
        
        $students = $query->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'faculty' => $user->studentProfile->faculty ?? '',
                'course' => $user->studentProfile->course ?? '',
                'group' => $user->studentProfile->group_name ?? '',
                'tutor' => $user->studentProfile->tutor ?? '',
                'rent_address' => $user->studentProfile->rent_address ?? '',
                'rent_map_url' => $user->studentProfile->rent_map_url ?? '',
                'gender' => $user->studentProfile->gender ?? '',
            ];
        });
        
        return response()->json([
            'success' => true,
            'total' => $students->count(),
            'data' => $students,
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function filterRents(Request $request): JsonResponse
    {
        try {
            $query = Rent::with('images');

            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            if ($request->filled('location')) {
                $query->where('location', 'like', "%{$request->location}%");
            }

            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            if ($request->filled('sort_by')) {
                $sortBy = $request->sort_by; 
                $sortOrder = $request->get('sort_order', 'asc'); 
                $query->orderBy($sortBy, $sortOrder);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
                });
            }


            $rents = $query->latest()->get();

            return response()->json([
                'success' => true,
                'total' => $rents->count(),
                'data' => $rents,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function studentRentPriceChart(): JsonResponse
    {
        try {
            $rents = Rent::select('price')->get();

            if ($rents->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'chart' => [],
                    'message' => 'Maʼlumot topilmadi.',
                ]);
            }

            $ranges = [
                '0–400 ming' => [0, 400000],
                '400–500 ming' => [400001, 450000],
                '500–600 ming' => [450001, 500000],
                '600+ ming' => [500001, INF],
            ];

            $data = [];
            $total = $rents->count();

            foreach ($ranges as $label => [$min, $max]) {
                $count = $rents->whereBetween('price', [$min, $max == INF ? PHP_INT_MAX : $max])->count();
                $percentage = round(($count / $total) * 100, 1);

                $data[] = [
                    'price_range' => $label,
                    'count' => $count,
                    'percentage' => $percentage,
                ];
            }

            return response()->json([
                'success' => true,
                'chart' => $data,
                'total_rents' => $total,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
   
