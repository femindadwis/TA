<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Driver_lokasi, Lokasi, User};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DriverLokasiController extends Controller
{
    public function index()
    {
        $data = [
            "driver_lokasi" => Driver_lokasi::all(),
            "user" => User::where('level', '3')->get(),
            "lokasi" => Lokasi::all()
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

        $data = [
            "driver_lokasi" => Driver_lokasi::where('id',$id)->get(),
            "user" => User::where('level', '3')->get(),
            "lokasi" => Lokasi::all()
        ];
        return view('driver_lokasi/driver_lokasi_edit', $data);
    }


    public function update(Request $request)
    {

            $id = $request->input('id');
            $user_id = $request->input('user_id');
            $lokasi_ids = $request->input('lokasi_id');

            // Convert the array of lokasi_ids to a comma-separated string
            $lokasi_id = implode(',', $lokasi_ids);

            $driverLokasi = Driver_lokasi::find($id);
            $driverLokasi->user_id = $user_id;
            $driverLokasi->lokasi_id = $lokasi_id;
            $driverLokasi->save();

        // alihkan halaman ke halaman driver
        return redirect('/driver_lokasi/driver_lokasi')->with('success', 'driver Telah di Ubah!');
    }

    public function hapus($id)
    {
        // menghapus data driver berdasarkan id yang dipilih
        DB::table('driver_lokasis')->where('id', $id)->delete();

        // alihkan halaman ke halaman driver
        return redirect('/driver_lokasi/driver_lokasi');

    }

    public function lokasi()
{
    $user = auth()->user()->id;

    $data = [
        "driver_lokasi" => Driver_lokasi::where('user_id', $user)->get(),
        "user" => User::where('level', '3')->get(),
        "lokasi" => Lokasi::all()
    ];

    return view('driver_lokasi.lokasi', $data);
}


}
