<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AutentikasiController extends Controller
{
    public function register()
    {
        return view("auth.register");
    }

    public function post_register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "level" => 2
        ]);

        return redirect("/login");
    }
    
    public function login()
    {
        return view("auth.login");
    }

    public function post_login(Request $request)
    {
        $validasi = $this->validate($request, [
            "email" => ["required", "string", "email", "max:255"],
            "password" => ["required", "string", "min:8"]
        ]);

        if (Auth::attempt($validasi)) {
            $request->session()->regenerate();

            return redirect()->intended("/dashboard");
        } else {
            return back();
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect("/login");
    }
}
