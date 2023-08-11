<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;
use App\Models\Jarak;
use App\Models\Driver;
use App\Models\Driver_lokasi;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class JarakController extends Controller
{
    public function index()
    {

        $data = [
            "driver" => Driver::all(),

        ];
        toast('Sebelum membuka jarak isi terlebih dahulu data lokasi driver!','info');
        return view('jarak.jarak', $data);
    }

    public function jarak()
    {
        $user = auth()->user()->id;
        $driver = Driver::where('user_id', $user)->first();
        $user_id = $driver->user_id;
        $driver_lokasi = Driver_lokasi::where('user_id', $user_id)->get();
        // Ambil semua lokasi dari tabel driver_lokasi berdasarkan user_id
        $driverLocations = Driver_Lokasi::where('user_id', $user_id)->get();

        // Ambil seluruh data dari model Lokasi yang sesuai dengan lokasi_id pada driver_lokasi
        $locationIds = $driverLocations->pluck('lokasi_id');
        $locations = Lokasi::whereIn('id', $locationIds)->get();
        $jarakData = Jarak::select('loc_1', 'loc_2', 'distance')->get()->toArray();

$jarak = [];
foreach ($jarakData as $data) {
    $jarak[$data['loc_1']][$data['loc_2']] = $data['distance'];
}

if (empty($jarakData)) {
    $distances = $this->calculateDistances($locations);

    foreach ($distances as $loc1Id => $distancesToOther) {
        foreach ($distancesToOther as $loc2Id => $distance) {
            Jarak::create([
                'loc_1' => $loc1Id,
                'loc_2' => $loc2Id,
                'distance' => $distance,
            ]);

            $jarak[$loc1Id][$loc2Id] = $distance;
        }
    }
} else {
    $newDistances = $this->calculateDistances($locations);

    foreach ($newDistances as $loc1Id => $distancesToOther) {
        foreach ($distancesToOther as $loc2Id => $distance) {
            if (!isset($jarak[$loc1Id][$loc2Id])) {
                Jarak::create([
                    'loc_1' => $loc1Id,
                    'loc_2' => $loc2Id,
                    'distance' => $distance,
                ]);

                $jarak[$loc1Id][$loc2Id] = $distance;
            }
        }
    }
}
        $data = [
            'driver' => $driver,
            'user' => User::all(),
            'locations' => $locations,
            // 'distances' => $distances,
            'driver_lokasi' => $driver_lokasi,
            'jarak' => $jarak,
        ];
        // dd($totaljarak);
        return view('jarak.jarak_driver', $data);
    }


    public function detail($id)
    {
        $driver = Driver::where('id', $id)->first();
        $user_id = $driver->user_id;
        $driver_lokasi = Driver_lokasi::where('user_id', $user_id)->get();
        // Ambil semua lokasi dari tabel driver_lokasi berdasarkan user_id
        $driverLocations = Driver_Lokasi::where('user_id', $user_id)->get();

        $locationIds = $driverLocations->pluck('lokasi_id');
        $locations = Lokasi::whereIn('id', $locationIds)->get();
       $jarakData = Jarak::select('loc_1', 'loc_2', 'distance')->get()->toArray();

$jarak = [];
foreach ($jarakData as $data) {
    $jarak[$data['loc_1']][$data['loc_2']] = $data['distance'];
}

if (empty($jarakData)) {
    $distances = $this->calculateDistances($locations);

    foreach ($distances as $loc1Id => $distancesToOther) {
        foreach ($distancesToOther as $loc2Id => $distance) {
            Jarak::create([
                'loc_1' => $loc1Id,
                'loc_2' => $loc2Id,
                'distance' => $distance,
            ]);

            $jarak[$loc1Id][$loc2Id] = $distance;
        }
    }
} else {
    $newDistances = $this->calculateDistances($locations);

    foreach ($newDistances as $loc1Id => $distancesToOther) {
        foreach ($distancesToOther as $loc2Id => $distance) {
            if (!isset($jarak[$loc1Id][$loc2Id])) {
                Jarak::create([
                    'loc_1' => $loc1Id,
                    'loc_2' => $loc2Id,
                    'distance' => $distance,
                ]);

                $jarak[$loc1Id][$loc2Id] = $distance;
            }
        }
    }
}
        $data = [
            'driver' => $driver,
            'user' => User::all(),
            'locations' => $locations,
            // 'distances' => $distances,
            'jarak' => $jarak,
            'driver_lokasi' => $driver_lokasi,

        ];
        // dd($totaljarak);
        return view('jarak.jarak_detail', $data);
    }

    // private function calculateDistances($locations)
    // {
    //     $apiKey = 'AIzaSyCfDg7Rknio90wPC0XaxJ6-l9JKppBygpU';
    //     $distances = [];

    //     $origins = $destinations = [];

    //     foreach ($locations as $location) {
    //         $origins[] = $location->lat . ',' . $location->lng;
    //         $destinations[] = $location->lat . ',' . $location->lng; // Use the same locations for origins and destinations
    //     }

    //     $origins = implode('|', $origins);
    //     $destinations = implode('|', $destinations);

    //     $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$origins}&destinations={$destinations}&key={$apiKey}";

    //     $response = Http::get($url);

    //     if ($response->ok()) {
    //         $data = $response->json();

    //         dd($data); // Dump the response data for debugging

    //         foreach ($data['rows'] as $i => $row) {
    //             foreach ($row['elements'] as $j => $element) {
    //                 if ($element['status'] == 'OK' && isset($element['distance']['value'])) {
    //                     $distances[$locations[$i]->id][$locations[$j]->id] = $element['distance']['value'] / 1000;
    //                 }
    //             }
    //         }
    //     }

    //     return $distances;
    // }



    private function calculateDistances($locations)
    {
        $apiKey = 'pk.eyJ1IjoiZmVtaW5kYTE2IiwiYSI6ImNsa25sc243bjB4czEzZG1tOTFxOXRmd2gifQ.LfN9gOS8caeDzvmoRIgDGQ';
        $coordinates = [];

        foreach ($locations as $location) {
            $coordinates[] = $location->lng . ',' . $location->lat;
        }

        $coordinates = implode(';', $coordinates);

        $url = "https://api.mapbox.com/directions-matrix/v1/mapbox/driving/{$coordinates}?access_token={$apiKey}&annotations=distance";
        $response = Http::get($url);

        if ($response->ok()) {
            $data = $response->json();

            // Initialize the distances array as an empty array
            $distances = [];

            foreach ($data['distances'] as $i => $row) {
                // $i corresponds to the index of the origin location in the locations array
                $originId = $locations[$i]->id;

                foreach ($row as $j => $distance) {
                    // $j corresponds to the index of the destination location in the locations array
                    $destinationId = $locations[$j]->id;

                    // Convert the distance from meters to kilometers and format it with two decimal places
                    $distances[$originId][$destinationId] = number_format($distance / 1000, 2);
                }
            }

            return $distances;
        }
        return [];
    }

    public function simpanJarak(Request $request)
    {
        $validatedData = $request->validate([
            'loc_1' => 'required|integer',
            'loc_2' => 'required|integer',
            'distance' => 'required|numeric',
        ]);

        $jarak = new Jarak();
        $jarak->loc_1 = $validatedData['loc_1'];
        $jarak->loc_2 = $validatedData['loc_2'];
        $jarak->distance = $validatedData['distance'];
        $jarak->save();

        return response()->json(['success' => true]);
    }

    public function reset($id)
{
    $driver = Driver::findOrFail($id);

    // Ambil semua lokasi yang terkait dengan driver dari tabel driver_lokasis
    $locationIds = Driver_lokasi::where('user_id', $driver->user_id)->pluck('lokasi_id')->toArray();

    // Hapus semua data jarak yang terkait dengan lokasi-lokasi tersebut
    DB::table('jaraks')->whereIn('loc_1', $locationIds)
                       ->orWhereIn('loc_2', $locationIds)
                       ->delete();

    return redirect('/jarak/jarak')->with('toast_success', 'Berhasil direset');
}

public function resetdriver($id)
{
    $driver = Driver::findOrFail($id);

    // Ambil semua lokasi yang terkait dengan driver dari tabel driver_lokasis
    $locationIds = Driver_lokasi::where('user_id', $driver->user_id)->pluck('lokasi_id')->toArray();

    // Hapus semua data jarak yang terkait dengan lokasi-lokasi tersebut
    DB::table('jaraks')->whereIn('loc_1', $locationIds)
                       ->orWhereIn('loc_2', $locationIds)
                       ->delete();

    return redirect('/jarak/jarak_driver')->with('toast_success', 'Berhasil direset');
}

}
