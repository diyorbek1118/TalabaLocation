<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminProfileRequest;
use App\Models\AdminProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $userId = Auth::id();
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


    public function update(Request $request, string $id)
    {
        //
    }

   
    public function destroy(string $id)
    {
        //
    }
}
