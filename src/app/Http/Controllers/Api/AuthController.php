<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

public function register(Request $request)
{
    $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:8',
    'phone' => ['required', 'regex:/^\+998\d{9}$/'], 
    'role' => 'required|in:student,renter,admin',
    ]);


    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'phone' => $request->phone,
        'role'=> $request->role,
    ]);

    return response()->json([
        'user'=>$user,
        'token' => $user->createToken($user->name)->plainTextToken,
    ]);

}
public function login(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Email yoki parol noto‘g‘ri'], 401);
    }

    return response()->json([
        'token' => $user->createToken($user->name)->plainTextToken,
    ]);
}

public function logout(Request $request): JsonResponse
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['status' => 200]);
}
}
