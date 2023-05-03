<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = DB::table('lokasi')->get();

        // mengirim data pegawai ke view index
        // return view('lokasi/lokasi',['lokasi' => $lokasi]);
        return view ('lokasi/lokasi', ['lokasi' => $lokasi]);
    }

    public function tambah()
    {

        // memanggil view tambah
        return view('lokasi/lokasi_tambah');

    }

    public function store(Request $request)
    {
        // insert data ke table lokasi
        DB::table('lokasi')->insert([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);
        // alihkan halaman lokasi
        return redirect('/lokasi/lokasi');

    }

    public function edit($id)
    {

        $lokasi = DB::table('lokasi')->where('id',$id)->get();

        return view('lokasi/lokasi_edit',['lokasi' => $lokasi]);
    }


    public function update(Request $request)
    {
        // update data lokasi

        DB::table('lokasi')->where('id',$request->id)->update([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);
        // alihkan halaman ke halaman lokasi
        return redirect('/lokasi/lokasi')->with('success', 'lokasi Telah di Ubah!');
    }

    public function hapus($id)
    {
        // menghapus data lokasi berdasarkan id yang dipilih
        DB::table('lokasi')->where('id',$id)->delete();

        // alihkan halaman ke halaman lokasi
        return redirect('/lokasi/lokasi');

    }
}
