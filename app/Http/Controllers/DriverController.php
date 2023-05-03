<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index()
    {
        $driver = DB::table('driver')->get();

        // mengirim data pegawai ke view index
        // return view('driver/driver',['driver' => $driver]);
        return view ('driver/driver', ['driver' => $driver]);
    }

    public function tambah()
    {

        // memanggil view tambah
        return view('driver/driver_tambah');

    }

    public function store(Request $request)
    {
        // insert data ke table driver
        DB::table('driver')->insert([
            'name' => $request->name,
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
