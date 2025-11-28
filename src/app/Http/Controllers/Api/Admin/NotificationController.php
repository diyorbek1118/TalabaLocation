<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestCommit;
use App\Models\Notification;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function commit(Request $request): JsonResponse
    {
        try {
            if (Auth::check()) {
                $validate = $request->validate([
                    'message' => 'required|string',
                    'type' => 'nullable|string',
                ]);

                $notification = Notification::create([
                    'sender_id' => Auth::id(),
                    'type' => $request->type,
                    'message' => $request->message,
                    'status' => 'unread',
                ]);

                return response()->json([
                    'success' => true,
                    'renter_notification' => $notification,
                ], 201);
            } else {
                $validate = $request->validate([
                    'name' => 'required|string|max:255',
                    'surname' => 'nullable|string|max:255',
                    'phone' => 'nullable|string|max:20',
                    'email' => 'nullable|email',
                    'message' => 'required|string',
                ]);

                $guestCommit = GuestCommit::create([
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'message' => $request->message,
                    'status' => 'unread',
                ]);

                return response()->json([
                    'success' => true,
                    'student_notification' => $guestCommit,
                ], 201);
            }
        } catch (Exception $e) {
            Log::error('Commit yaratishda xatolik: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getCommits(): JsonResponse
    {
        try {
            $notification = Notification::with('sender')->where('status', 'unread')->latest()->get();

            $guestCommit = GuestCommit::where('status', 'unread')->latest()->get();

            return response()->json([
                'success' => true,
                'renter_notification' => $notification,
                'student_notification' => $guestCommit,
            ], 200);
        } catch (Exception $e) {
            Log::error(message: 'Commitlarni olishda xatolik: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

  public function markAsRead($id): JsonResponse
{
    try {
        $item = Notification::find(id: $id);

        if (!$item || !$item->sender_id) {
            $item = GuestCommit::find($id);
        }

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Xabar topilmadi.'
            ], 404);
        }

        if ($item->status === 'unread') {
            $item->update(['status' => 'read']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Xabar o‘qilgan deb belgilandi.',
            'data' => $item
        ], 200);

    } catch (\Throwable $e) { 
        Log::error('Mark as read error: '.$e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getPendingRents(): JsonResponse{
    try{
        $pendingRent = Rent::where('status','pending')->get();
        if($pendingRent->isEmpty()){
            return response()->json([
                'success' => false,
                'message' => 'Tasdiqlanmagan ijara uy topilmadi.'
            ], 404);
        }
        return response()->json([
            'success'=> true,
            'data' => $pendingRent
        ],200);
    }catch (Exception $e) {
         Log::error(message: 'Ijaralarni olishda xatolik: '.$e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function updateStatus($id,$status){
    try {
        
        $item = Rent::find($id);

         if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Bunday ID bo‘yicha ijara topilmadi.',
            ], 404);
        }

        $item->status = $status;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Status '.$status.' deb belgilandi.',
            'data' => $item
        ], 200);

    } catch (\Throwable $e) { 
        Log::error('Update status error: '.$e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
