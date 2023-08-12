<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Driver;

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
            $driver = Driver::where('user_id', $user->id)->get();
            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'user' => $driver[0],
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
