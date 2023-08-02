<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Lokasi;


class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();

        // mengirim data pegawai ke view index
        // return view('lokasi/lokasi',['lokasi' => $lokasi]);
        return view('lokasi.lokasi', compact('lokasi'));
    }

    public function tambah()
    {

        // memanggil view tambah
        return view('lokasi/lokasi_tambah');

    }

    public function store(Request $request)
    {
        if($request->hasFile('foto')){
            $foto = $request->file('foto')->store('Lokasi');
        }
        // insert data ke table lokasi
        DB::table('lokasi')->insert([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'lng' => $request->lng,
            'lat' => $request->lat,
            'foto' => $foto,
        ]);
        // alihkan halaman lokasi
        return redirect('/lokasi/lokasi')->with('toast_success', 'Lokasi telah ditambahkan!');

    }

    public function edit($id)
    {

        $lokasi = DB::table('lokasi')->where('id',$id)->get();

        return view('lokasi/lokasi_edit',['lokasi' => $lokasi]);
    }


    public function update(Request $request)
    {
        // update data lokasi
        if($request->hasFile('foto')){
            $foto = $request->file('foto')->store('Lokasi');
        }
        DB::table('lokasi')->where('id',$request->id)->update([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'lng' => $request->lng,
            'lat' => $request->lat,
            'foto' => $foto,
        ]);
        // alihkan halaman ke halaman lokasi
        return redirect('/lokasi/lokasi')->with('toast_success', 'Lokasi telah diubah!');
    }

    public function hapus($id)
    {
        // menghapus data lokasi berdasarkan id yang dipilih
        DB::table('lokasi')->where('id',$id)->delete();

        // alihkan halaman ke halaman lokasi
        return redirect('/lokasi/lokasi')->with('toast_success', 'Lokasi telah dihapus!');

    }
}
