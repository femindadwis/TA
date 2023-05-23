<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class DriverController extends Controller
{
    public function index()
    {
        $driver = DB::table('driver')->get();
        $user = DB::table('users')->get();
        // mengirim data pegawai ke view index
        // return view('driver/driver',['driver' => $driver]);
        return view ('driver/driver', ['driver' => $driver], ['user' => $user]);
    }

    public function tambah()
    {
        $user = DB::table('users')->where('level', '3')->get();
        // memanggil view tambah
        return view('driver/driver_tambah', ['user' => $user]);

    }

    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        // insert data ke table driver
        DB::table('driver')->insert([
            'user_id' => $request->user_id,
            'name' => $user['name'],
            'email' => $request->email,
            'alamat' => $request->alamat,
        ]);
        // alihkan halaman driver
        return redirect('/driver/driver');

    }

    public function edit($id)
    {

        $driver = DB::table('driver')->where('id',$id)->get();

        return view('driver/driver_edit',['driver' => $driver]);
    }


    public function update(Request $request)
    {
        // update data driver

        DB::table('driver')->where('id',$request->id)->update([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
        ]);
        // alihkan halaman ke halaman driver
        return redirect('/driver/driver')->with('success', 'driver Telah di Ubah!');
    }

    public function hapus($id)
    {
        // menghapus data driver berdasarkan id yang dipilih
        DB::table('driver')->where('id',$id)->delete();

        // alihkan halaman ke halaman driver
        return redirect('/driver/driver');

    }
}
