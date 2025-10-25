<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

public function register(RegisterRequest $request)
{
    $request->validated();


    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'role'=> 'renter',
    ]);

    return response()->json([
        'user'=>$user,
        'token' => $user->createToken($user->name)->plainTextToken,
    ]);

}
public function login(LoginRequest $request): JsonResponse
{
    $request->validated();

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Email yoki parol noto‘g‘ri'], 401);
    }

    return response()->json([
        'user' => $user,
        'token' => $user->createToken($user->name)->plainTextToken,
    ]);
}

public function logout(Request $request): JsonResponse
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['status' => 200]);
}
}
