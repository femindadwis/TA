<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Driver_lokasi, Lokasi, User, Driver};
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
            "driver" => Driver::all()->sortBy('name'),
            "lokasi" => Lokasi::all()->sortBy('name')
        ];


        return view('driver_lokasi.driver_lokasi_tambah', $data);
    }

    public function store(Request $request)
    {

        $user_id = $request->user_id;
        $lokasi_ids = $request->input('lokasi_id');

        $data = [];
        foreach ($lokasi_ids as $lokasi_id) {
            $data[] = [
                "user_id" => $user_id,
                "lokasi_id" => $lokasi_id,
            ];
        }

        DB::table('driver_lokasis')->insert($data);
        return redirect('/driver_lokasi/driver_lokasi')->with('toast_success', 'Lokasi driver Telah ditambahkan!');
    }

    public function edit($id)
    {
        $driverLokasi = Driver_lokasi::where('user_id', $id)->get();
        $my_lokasi = [];

        foreach ($driverLokasi as $dl) {
            $lokasi = Lokasi::find($dl->lokasi_id);
            $my_lokasi[] = $lokasi;
        }

        $data = [
            "my_lokasi" => $my_lokasi,
            "user_id" => $id,
            "driver" => Driver::all()->sortBy('name'),
            "lokasi" => Lokasi::all()->sortBy('name'),
        ];
        return view('driver_lokasi/driver_lokasi_edit', $data);
    }


    public function update(Request $request)
    {
        $userId = $request->user_id;
        $lokasiIds = $request->lokasi_id;

        Driver_lokasi::where('user_id', $userId)->delete();

        if ($lokasiIds) {
            foreach ($lokasiIds as $lokasiId) {
                DB::table('driver_lokasis')->insert([
                    'user_id' => $userId,
                    'lokasi_id' => $lokasiId,
                ]);
            }
        }
        return redirect('/driver_lokasi/driver_lokasi')->with('toast_success', 'Lokasi driver Telah diubah!');
    }


    public function hapus($id)
    {
        // menghapus data driver berdasarkan id yang dipilih
        DB::table('driver_lokasis')->where('id', $id)->delete();

        // alihkan halaman ke halaman driver
        return redirect('/driver_lokasi/driver_lokasi')->with('toast_success', 'Lokasi driver Telah dihapus!');
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

    public function lokasiedit($id)
    {

        $lokasi = DB::table('lokasi')->where('id',$id)->get();

        return view('driver_lokasi/lokasidriver_edit',['lokasi' => $lokasi]);
    }


    public function lokasiupdate(Request $request)
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
        return redirect('driver_lokasi/lokasi')->with('toast_success', 'Lokasi telah diubah!');
    }
}
