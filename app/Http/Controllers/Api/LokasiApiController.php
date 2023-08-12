<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Driver_lokasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Lokasi;
use App\Models\Driver;
use App\Models\Route;

class LokasiApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        $driverLokasi = Driver_lokasi::where('user_id', $user->id)->get();
        $lokasis = [];

        foreach ($driverLokasi as $dl) {
            $lokasi = Lokasi::find($dl->lokasi_id);
            $foto = null;
            if ($lokasi->foto) {
                $foto = Storage::disk('public')->get("{$lokasi->foto}");
                $foto = base64_encode($foto);
            }
            $lokasi->foto = $foto;
            $lokasi->fotoName = $lokasi->foto;
            $lokasis[] = $lokasi;
        }
        return response()->json($lokasis);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'alamat' => 'required',
            'long' => 'required',
            'lat' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 500);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('Lokasi', 'public');
        }

        $lokasi = new Lokasi();
        $lokasi->name = $request->name;
        $lokasi->alamat = $request->alamat;
        $lokasi->lng = $request->long;
        $lokasi->lat = $request->lat;
        $lokasi->foto = $foto;
        $lokasi->save();

        $driverLokasi = new Driver_lokasi();
        $driverLokasi->user_id = Auth()->user()->id;
        $driverLokasi->lokasi_id = $lokasi->id;
        $driverLokasi->save();

        return response()->json(['message' => 'Lokasi created!']);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'alamat' => 'required',
            'long' => 'required',
            'lat' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 500);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('Lokasi', 'public');
        }

        $lokasi = Lokasi::find($request->id);
        $lokasi->name = $request->name;
        $lokasi->alamat = $request->alamat;
        $lokasi->lng = $request->long;
        $lokasi->lat = $request->lat;
        $lokasi->foto = $foto;
        $lokasi->save();

        return response()->json(['message' => 'Lokasi updated!']);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $lokasi = Lokasi::find($request->id);
        // cek user
        if ($user->id == null) {
            return response()->json(['error' => 'Unauthorized']);
        }
        Lokasi::where('id', $request->id)->delete();
        return response()->json(['message' => 'Lokasi deleted!']);
    }

    public function rute()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->get();
        $rute = Route::where('driver_id', $driver[0]->id)->get();
        $urutanLokasi = [];
        $arrayLokasi = explode('-', $rute[0]->urutan);

        foreach ($arrayLokasi as $id) {
            $lokasi = Lokasi::find($id);
            $urutanLokasi[] = $lokasi;
        }
        return response()->json($urutanLokasi);
    }
}
