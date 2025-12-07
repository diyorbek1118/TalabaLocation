<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminProfileRequest;
use App\Http\Requests\UpdateAdminProfileRequest;
use App\Models\AdminProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function index(){
        $adminList = User::where('role','admin')
        ->with('adminProfile')
        ->get();

        if($adminList->isEmpty()){
            return response()->json([
            'message' => 'Admins not found'
        ], 404);
        }
         return response()->json([
        'admins' => $adminList
    ], 200);
    } 

    
    public function store(StoreAdminProfileRequest $store)
    {
        $data = $store->validated();

        $userId = $store->user_id;
        $profile = AdminProfile::where('user_id', $userId)->first();

    if ($profile) {
        return response()->json([
            'success' => false,
            'message' => 'Profile already exists. Please update it.'
        ], 400);
    }

        $profile = new AdminProfile();
        $profile->user_id = $userId;
        $profile->position = $data['position'] ?? null;
        $profile->province = $data['province'] ?? null;
        $profile->district = $data['district'] ?? null;
        if($store->hasFile('profile_image')){
            $path = $store->file('profile_image')->store('profile_images','public');
            
            $profile->profile_image = $path;
        }

        $profile->save();

           return response()->json([
            'success'=> true
        ],200);

    }

    
    public function show($id)
        {
            $profile = User::with('adminProfile')->findOrFail(Auth::id());

            return response()->json([
                'admin' => $profile
            ], 200);
        }


  public function update(UpdateAdminProfileRequest $request, AdminProfile $admin)
{
    $profile = $request->validated();

    if ($request->hasFile('profile_image')) {
        if ($admin->profile_image && Storage::disk('public')->exists($admin->profile_image)) {
            Storage::disk('public')->delete($admin->profile_image);
        }

        $path = $request->file('profile_image')->store('profile_images','public');
        $admin->profile_image = $path;
    }

    $admin->position = $profile['position'] ?? null;
    $admin->province = $profile['province'] ?? null;
    $admin->district = $profile['district'] ?? null;
    $admin->save();

    return response()->json([
        'success'=> true
    ], 200);
}
   
    public function destroy(string $id)
    {
        $deleteAdmin = User::find($id);
        if(!$deleteAdmin){
            return response()->json([
                'message' => 'Admin not found'
            ], 404);
    }
    $deleteAdmin->delete();
    return response()->json([
        'message' => 'Admin deleted successfully'
    ], 200); 

    }
}
