<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        // insert data ke table user
        DB::table('users')->insert([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);
        // alihkan halaman user
        return redirect('/user/user');

    }

    public function edit($id)
    {

        $user = DB::table('users')->where('id',$id)->get();

        return view('user/user_edit',['user' => $user]);
    }


    public function update(Request $request)
    {
        // update data user

        DB::table('users')->where('id',$request->id)->update([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);
        // alihkan halaman ke halaman user
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
