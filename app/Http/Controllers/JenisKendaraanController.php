<?php

namespace App\Http\Controllers;

use App\Models\Jeniskendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class JenisKendaraanController extends Controller
{
    public function index()
    {
    $jenis_kendaraan = Jeniskendaraan::all();
    return view('jenis_kendaraan.jenis_kendaraan', compact('jenis_kendaraan'));
    }

    public function tambah()
    {

        // memanggil view tambah
        return view('jenis_kendaraan/jenis_kendaraan_tambah');

    }

    public function store(Request $request)
    {

        // insert data ke table lokasi
        DB::table('jeniskendaraan')->insert([
            'jenis_kendaraan' => $request->jenis_kendaraan,
        ]);
        // alihkan halaman lokasi
        return redirect('/jenis_kendaraan/jenis_kendaraan');

    }

    public function edit($id)
    {

        $jenis_kendaraan = DB::table('jeniskendaraan')->where('id',$id)->get();

        return view('jenis_kendaraan/jenis_kendaraan_edit',['jenis_kendaraan' => $jenis_kendaraan]);
    }


    public function update(Request $request)
    {

        DB::table('jeniskendaraan')->where('id',$request->id)->update([
            'jenis_kendaraan' => $request->jenis_kendaraan,

        ]);
        // alihkan halaman ke halaman lokasi
        return redirect('/jenis_kendaraan/jenis_kendaraan')->with('success', 'jenis_kendaraan Telah di Ubah!');
    }

    public function hapus($id)
    {
        // menghapus data lokasi berdasarkan id yang dipilih
        DB::table('jeniskendaraan')->where('id',$id)->delete();

        // alihkan halaman ke halaman lokasi
        return redirect('/jenis_kendaraan/jenis_kendaraan');

    }
}
