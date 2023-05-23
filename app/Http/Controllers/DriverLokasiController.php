<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Driver_lokasi, Lokasi, User};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverLokasiController extends Controller
{
    public function index()
    {
        $data = [
            "driver_lokasi" => Driver_lokasi::all()
        ];
        return view('driver_lokasi.driver_lokasi', $data);
    }

    public function tambah()
    {
        $data = [
            "user" => User::where('level', '3')->get(),
            "lokasi" => Lokasi::all()
        ];


        return view('driver_lokasi.driver_lokasi_tambah', $data);

    }

    public function store(Request $request)
    {

        $user_id = $request->user_id;
        $lokasi_ids = $request->input('lokasi_id');

        $data = [];
        foreach($lokasi_ids as $lokasi_id){
            $data[] = [
            "user_id" => $user_id,
            "lokasi_id" => $lokasi_id,
        ];
        }

        DB::table('driver_lokasis')->insert($data);
        return redirect('/driver_lokasi/driver_lokasi');

    }

    public function edit($id)
    {

        $driver_lokasi = DB::table('driver_lokasis')->where('id', $id)->get();

        return view('driver_lokasi/driver_lokasi_edit', ['driver_lokasi' => $driver_lokasi]);
    }


    public function update(Request $request)
    {
        // update data driver

        // DB::table('driver')->where('id',$request->id)->update([
        //     'user_id' => $request->user_id,
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'alamat' => $request->alamat,
        // ]);
        // alihkan halaman ke halaman driver
        return redirect('/driver_lokasi/driver_lokasi')->with('success', 'driver Telah di Ubah!');
    }

    public function hapus($id)
    {
        // menghapus data driver berdasarkan id yang dipilih
        DB::table('driver_lokasi')->where('id', $id)->delete();

        // alihkan halaman ke halaman driver
        return redirect('/driver_lokasi/driver_lokasi');

    }
}
