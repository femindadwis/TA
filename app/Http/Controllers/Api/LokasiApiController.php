<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Driver_lokasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


use App\Models\Lokasi;

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
                $foto = Storage::disk('public')->get("foto_tps/{$lokasi->foto}");
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
            'fotoName' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 500);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            // Nama file yang disimpan sesuai dengan fotoName
            $filename = $request->fotoName;
            // Menyimpan foto dengan nama file yang telah ditentukan
            $foto->storeAs('foto_tps', $filename, 'public');
        }

        $lokasi = new Lokasi();
        $lokasi->name = $request->name;
        $lokasi->alamat = $request->alamat;
        $lokasi->lng = $request->long;
        $lokasi->lat = $request->lat;
        $lokasi->foto = $request->fotoName;
        $lokasi->save();

        $driverLokasi = new Driver_lokasi();
        $driverLokasi->user_id = Auth()->user()->id;
        $driverLokasi->lokasi_id = $lokasi->id;
        $driverLokasi->save();

        return response()->json(['message' => 'Lokasi created!']);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $lokasi = Lokasi::find($request->id);
        //cek user
        if ($user->id != $lokasi->user_id) {
            return response()->json(['error' => 'Unauthorized']);
        }
        //validasi data masuk
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'alamat' => 'required',
            'lng' => 'required',
            'lat' => 'required',
        ]);
        //bila gagal validasi
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        //update note
        Lokasi::where('id', $request->id)->update($request->all());

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

    public function getImage($id)
    {
        $data = Lokasi::find($id);
        return response()->file(public_path("storage/foto_tps/$data->foto"));
    }
}
