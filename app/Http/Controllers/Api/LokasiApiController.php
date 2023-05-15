<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        $lokasi = Lokasi::where('user_id', $user->id)->get();
        return response()->json(['lokasi' => $lokasi]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'alamat' => 'required',
            'long' => 'required',
            'lat' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user = Auth::user();
        $lokasi = new Lokasi();
        $lokasi->user_id = $user->id;
        $lokasi->name = $request->name;
        $lokasi->alamat = $request->alamat;
        $lokasi->lng = $request->long;
        $lokasi->lat = $request->lat;
        $lokasi->save();
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
            'title' => 'required',
            'description' => 'required',
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
        if ($user->id != $lokasi->user_id) {
            return response()->json(['error' => 'Unauthorized']);
        }
        Lokasi::where('id', $request->id)->delete();
        return response()->json(['message' => 'Lokasi deleted!']);
    }
}
