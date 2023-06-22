<?php
namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AutentikasiController extends Controller
{
    public function register()
    {
        return view("auth.register");
    }

    public function post_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            "name" => $request->name,
            "username" => $request->username,
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
            "username" => ["required", "string", "max:255"],
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
