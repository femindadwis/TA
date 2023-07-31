<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        if (auth()->user()->level == 2) {
           $user = User::whereIn('level', [2, 3])->get();
        }

        // mengirim data pegawai ke view index
        // return view('user/user',['user' => $user]);
        return view ('user/user', ['user' => $user]);
    }

    public function tambah()
    {

        // memanggil view tambah
        return view('user/user_tambah');

    }

    public function store(Request $request)
    {
        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ];

        if ($request->level == 2) {
            $user = User::create($userData);

            // Simpan user_id ke tabel admins
            Admin::create([
                'user_id' => $user->id,
                // Kolom-kolom lain yang Anda butuhkan untuk tabel admins
            ]);
        } else {
            User::create($userData);
        }

        return redirect('/user/user');

    }

    public function edit($id)
    {

        $user = DB::table('users')->where('id',$id)->get();
        // $user = User::findOrFail($id);
        return view('user/user_edit',['user' => $user]);
    }


    public function update(Request $request)
    {
        // update data user

        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ];

        User::where('id', $request->id)->update($userData);

        return redirect('/user/user')->with('success', 'User Telah di Ubah!');
    }

    public function hapus($id)
    {
        // menghapus data user berdasarkan id yang dipilih
        DB::table('users')->where('id',$id)->delete();

        // alihkan halaman ke halaman user
        return redirect('/user/user');

    }
}
