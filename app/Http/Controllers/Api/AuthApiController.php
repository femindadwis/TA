<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized']);
        }

        $user = $request->user();
        // Hapus token lama
        $user->tokens()->delete();
        // Generate token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'status' => 'success',
            'message' => 'Login successful',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete(); //ignore error
        return response()->json(['message' => 'Logout successful']);
    }
}
