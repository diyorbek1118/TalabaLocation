<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function getRole(Request $request)
    {
        if ($request->routeIs('admin.*')) {
            return 'admin';
        } elseif ($request->routeIs('renters.*')) {
            return 'renter';
        }
        return null;
    }

    public function index(Request $request)
    {
        $role = $this->getRole($request);

        $users = User::where('role',$role)->get();

        if($users->isEmpty()){
            return response()->json([
                'message'=> 'User not found'
            ],404);
        }
        return response()->json([
            'users' => $users
        ],200);
    }


    public function store(UserRequest $request)
    {
        $role = $this->getRole($request);
        $validated = $request->validated();
        
        $validated['role'] = $role;

         if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }
        User::create($validated);

        return response()->json([
            'success'=> true
        ],201);
    }


    public function show(Request $request, string $id)
    {
       $role = $this->getRole($request);
       $users = User::where('role',$role)->where('id',$id)->get();

       if($users->isEmpty()){
        return response()->json([
            'message'=> 'User not found'
        ],200);
       }

       return response()->json([
        'users'=> $users
       ],200);

    }


    public function update(UserRequest $request, string $id)
{
    $role = $this->getRole($request);

    $user = User::where('role', $role)->where('id', $id)->first();


    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $validated = $request->validated();
    if (empty($validated)) {
        return response()->json(['message' => 'No changes detected'], 200);
    }

    if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }

    $validated['role'] = $role;

    $user->update($validated);

    return response()->json([
        'success' => 'true'
    ], 200);
}


    public function destroy(Request $request, string $id)
    {
        $role = $this->getRole($request);
        $user = User::where('role', $role)->where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'success' => false
            ],404);
        }

        $user->delete();
        return response()->json([
            'success'=> true
        ],200);
    }
}
