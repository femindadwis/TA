<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class ForgotPasswordController extends Controller
{
    public function showResetForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $username = $request->input('username');

        // Check if the username exists in the database
        $user = User::where('username', $username)->first();

        if ($user) {
            // Generate a unique token
            $token = bin2hex(random_bytes(32));

            // Store the token and username in the password_resets table
            DB::table('password_resets')->insert([
                'username' => $username,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Redirect to the password reset form with the username and token
            return redirect()->route('password.reset', ['username' => $username, 'token' => $token]);
        } else {
            return redirect()->route('password.request')->with('error', 'Username tidak ditemukan.');
        }
    }

    public function showResetPasswordForm(Request $request, $username, $token)
    {
        return view('auth.reset-password', ['username' => $username, 'token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'token' => 'required',
            'password' => 'required|confirmed|min:8', // You can adjust validation rules as needed
        ]);

        // Validate the token and username from the password_resets table
        $resetData = DB::table('password_resets')
            ->where('username', $request->username)
            ->where('token', $request->token)
            ->first();

        if (!$resetData) {
            return redirect()->route('password.request')->with('error', 'Invalid token or username.');
        }

        // Update the user's password in the users table
        $user = User::where('username', $request->username)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token from the password_resets table
        DB::table('password_resets')
            ->where('username', $request->username)
            ->delete();

        // Redirect to the login page with a success message
        return redirect("/login")->with('toast_success', 'Reset password berhasil. Anda bisa login sekarang!.');
    }
}
