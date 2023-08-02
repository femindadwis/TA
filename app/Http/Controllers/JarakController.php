<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;
use App\Models\Jarak;
use App\Models\Driver;
use App\Models\Driver_lokasi;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class JarakController extends Controller
{
    public function index()
    {

        $data = [
            "driver" => Driver::all(),

        ];
        alert()->info('Info','Sebelum membuka jarak isi terlebih dahulu data lokasi driver!');
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

        $distances = $this->calculateDistances($locations);

        foreach ($distances as $loc1Id => $distancesToOther) {
            foreach ($distancesToOther as $loc2Id => $distance) {
                Jarak::updateOrCreate(
                    ['loc_1' => $loc1Id, 'loc_2' => $loc2Id],
                    ['distance' => $distance]
                );
            }
        }

        // Mendapatkan data jarak dari database
        $jarakData = Jarak::select('loc_1', 'loc_2', 'distance')->get()->toArray();

        // Mengonversi data jarak ke dalam bentuk matriks
        $jarak = [];
        foreach ($jarakData as $data) {
            $jarak[$data['loc_1']][$data['loc_2']] = $data['distance'];
        }
        ksort($jarak);
        foreach ($jarak as &$row) {
            ksort($row);
        }

        $data = [
            'driver' => $driver,
            'user' => User::all(),
            'locations' => $locations,
            'distances' => $distances,
            'driver_lokasi' => $driver_lokasi,

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

        // Ambil seluruh data dari model Lokasi yang sesuai dengan lokasi_id pada driver_lokasi
        $locationIds = $driverLocations->pluck('lokasi_id');
        $locations = Lokasi::whereIn('id', $locationIds)->get();

        $distances = $this->calculateDistances($locations);

        foreach ($distances as $loc1Id => $distancesToOther) {
            foreach ($distancesToOther as $loc2Id => $distance) {
                Jarak::updateOrCreate(
                    ['loc_1' => $loc1Id, 'loc_2' => $loc2Id],
                    ['distance' => $distance]
                );
            }
        }

        // Mendapatkan data jarak dari database
        $jarakData = Jarak::select('loc_1', 'loc_2', 'distance')->get()->toArray();

        // Mengonversi data jarak ke dalam bentuk matriks
        $jarak = [];
        foreach ($jarakData as $data) {
            $jarak[$data['loc_1']][$data['loc_2']] = $data['distance'];
        }

        ksort($jarak);
        foreach ($jarak as &$row) {
            ksort($row);
        }

        $data = [
            'driver' => $driver,
            'user' => User::all(),
            'locations' => $locations,
            'distances' => $distances,
            'jarak' => $jarak,
            'driver_lokasi' => $driver_lokasi,

        ];
        // dd($totaljarak);
        return view('jarak.jarak_detail', $data);
    }

    // fungsi itung jarak google maps api
    // private function calculateDistances($locations)
    // {
    //     $apiKey = 'AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg';
    //     $distances = [];

    //     foreach ($locations as $location) {
    //         $origins = $destinations = [];

    //         foreach ($locations as $otherLocation) {
    //             $origins[] = $location->lat . ',' . $location->lng;
    //             $destinations[] = $otherLocation->lat . ',' . $otherLocation->lng;
    //         }

    //         $origins = implode('|', $origins);
    //         $destinations = implode('|', $destinations);

    //         $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$origins}&destinations={$destinations}&key={$apiKey}";

    //         $response = Http::get($url);

    //         if ($response->ok()) {
    //             $data = $response->json();

    //             foreach ($data['rows'] as $i => $row) {
    //                 foreach ($row['elements'] as $j => $element) {
    //                     if ($element['status'] == 'OK' && isset($element['distance']['value'])) {
    //                         $distances[$location->id][$locations[$j]->id] = $element['distance']['value'] / 1000;
    //                     }
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

        // Return an empty array if the API request fails or no data is available
        return [];
    }

    // Fungsi untuk menghitung total jarak
    function calculateTotalDistances($rute, $jarak)
    {
        $totalJarak = 0;
        $jumlahTitik = count($rute);

        for ($i = 0; $i < $jumlahTitik - 1; $i++) {
            if (isset($rute[$i]) && isset($rute[$i + 1])) {
                $titik1 = $rute[$i];
                $titik2 = $rute[$i + 1];

                if (isset($jarak[$titik1][$titik2])) {
                    $totalJarak += $jarak[$titik1][$titik2];
                } else {
                    $totalJarak += 0; // Nilai default jika elemen tidak ada
                }
            } else {
                $totalJarak += 0;
            }
        }

        $titikAwal = $rute[0];
        $titikAkhir = $rute[$jumlahTitik - 1];
        if (isset($jarak[$titikAkhir][$titikAwal])) {
            $totalJarak += $jarak[$titikAkhir][$titikAwal];
        } else {

            $totalJarak += 0;
        }

        return $totalJarak;
    }
}
