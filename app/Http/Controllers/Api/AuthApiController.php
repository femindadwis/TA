<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Jika autentikasi berhasil
            $user = Auth::user();
            $user->tokens()->delete(); //jika pernah login hapus token lama
            $token = $user->createToken('auth_token')->plainTextToken; //ignore error

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
            ]);
        } else {
            // Jika autentikasi gagal
            return response()->json([
                'message' => 'Invalid username or password',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete(); //ignore error
        return response()->json(['message' => 'Logout successful']);
    }
}
