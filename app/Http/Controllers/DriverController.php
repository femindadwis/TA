<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\{Driver, User, Driver_lokasi, Lokasi, Jarak};
use Illuminate\Support\Facades\Http;
use App\Models\Jeniskendaraan;


class DriverController extends Controller
{
    public function index()
    {
        $data = [
            "driver" => Driver::all(),
            "user" => User::all(),
            "jeniskendaraan" => Jeniskendaraan::all()
        ];
        // dd($data);
        return view('driver.driver', $data);

    }

    public function tambah()
    {
        $data = [

            "user" =>  User::where('level', '3')->get(),
            "jenis_kendaraan" => Jeniskendaraan::all()
        ];
        // memanggil view tambah
        return view('driver/driver_tambah', $data);

    }

    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        // insert data ke table driver
        DB::table('driver')->insert([
            'user_id' => $request->user_id,
            'name' => $user['name'],
            'username' => $request->username,
            'alamat' => $request->alamat,
            'no_polisi' => $request->no_polisi,
            'no_telepon' => $request->no_telepon,
            'jeniskendaraan_id' => $request->jeniskendaraan_id,
        ]);
        // alihkan halaman driver
        return redirect('/driver/driver')->with('toast_success', 'Driver Telah ditambahkan!');

    }

    public function edit($id)
    {
        $data = [

            "driver" =>  Driver::where('id', $id)->get(),
            "user" => User::all(),
            "jenis_kendaraan" => Jeniskendaraan::all()
        ];
        return view('driver/driver_edit', $data);
    }


    public function update(Request $request)
    {
        // update data driver
        $user = User::findOrFail($request->user_id);
        DB::table('driver')->where('id',$request->id)->update([
            'user_id' => $request->user_id,
            'name' => $user['name'],
            'username' => $request->username,
            'alamat' => $request->alamat,
            'no_polisi' => $request->no_polisi,
            'no_telepon' => $request->no_telepon,
            'jeniskendaraan_id' => $request->jeniskendaraan_id,
        ]);
        // alihkan halaman ke halaman driver
        return redirect('/driver/driver')->with('toast_success', 'Driver Telah diubah!');
    }

    public function hapus($id)
    {
        // menghapus data driver berdasarkan id yang dipilih
        DB::table('driver')->where('id',$id)->delete();

        // alihkan halaman ke halaman driver
        return redirect('/driver/driver')->with('toast_success', 'Driver Telah dihapus!');

    }

}
