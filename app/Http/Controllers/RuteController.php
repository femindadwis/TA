<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Jarak;
use App\Models\Lokasi;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RuteController extends Controller
{
    public function index()
    {
         // Data tambahan
         $tpaPecuk = Lokasi::find(1)->toArray();
         // Data tambahan
         $driver = Driver::all();
         $endLocation = Lokasi::where('id', '!=', 1)->get()->toArray();

         $totalEndLocations = count($endLocation); // Jumlah total lokasi yang akan dibagi
         $totalDrivers = Driver::count();

         $locationsPerDriver = floor($totalEndLocations / $totalDrivers); // Jumlah lokasi per driver (pembulatan ke bawah)

         $remainingItems = $totalEndLocations % $totalDrivers; // Sisa lokasi setelah pembagian

         // Menginisialisasi array untuk menyimpan jumlah lokasi per driver
         $itemCounts = array_fill(0, $totalDrivers, $locationsPerDriver);

         // Memasukkan sisa lokasi ke driver pertama
         for ($i = 0; $i < $remainingItems; $i++) {
             $itemCounts[$i]++;
         }

         $data = array();
         $endLocationIndex = 0;
         for ($i = 0; $i < $totalDrivers; $i++) {
             $data[$i][0] = $tpaPecuk; // Memasukkan data TPA Pecuk ke setiap driver

             for ($j = 1; $j <= $itemCounts[$i]; $j++) {
                 $data[$i][$j] = $endLocation[$endLocationIndex];
                 $endLocationIndex++;
             }

        $locations = [];

        foreach ($data  as $key => $item) {
            foreach ($item as $key2 => $location) {

                $locations[$key][$key2] = [

                    $location["name"],
                    $location["lng"],
                    $location["lat"],
                ];
            }
        }}

        $lines = [];
        foreach ($data as $key => $item) {

            foreach ($item as $key2 => $location) {
                $lines[$key][$key2] = [
                    "lng" => $location["lng"],
                    "lat" => $location["lat"],
                ];
            }
        }
        // dd($lines);
        return view('rute.rute_gmaps', ['locations' => $locations, 'lines' => $lines, 'tpaPecuk' => $tpaPecuk, 'driver' => $driver]);
    }

    function getNearestLocation($startLat, $startLng, $locations)
    {
        $nearestLocation = null;
        $nearestDistance = null;

        foreach ($locations as $location) {
            $lat = $location['lat'];
            $lng = $location['lng'];

            $distance = $this->haversineDistance($startLat, $startLng, $lat, $lng);

            if ($nearestDistance === null || $distance < $nearestDistance) {
                $nearestLocation = $location;
                $nearestDistance = $distance;
            }
        }

        return $nearestLocation;
    }

    function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Radius of the earth in kilometers

        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLng = deg2rad(abs($lng2 - $lng1)); // Menggunakan nilai absolut delta longitude

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($deltaLng / 2) * sin($deltaLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }

    public function detail($id)
    {
        $driver = Driver::where('id', $id)->first();
        $tpaPecuk = Lokasi::find(1)->toArray();
        // Retrieve the locations related to the driver
        $endLocation = Lokasi::where('id', '!=', 1)->get()->toArray();

        $totalEndLocations = count($endLocation); // Total number of locations to be divided
        $totalDrivers = Driver::count();

        $locationsPerDriver = floor($totalEndLocations / $totalDrivers); // Number of locations per driver (rounded down)
        $remainingItems = $totalEndLocations % $totalDrivers; // Remaining locations after division

        // Initialize an array to store the number of locations per driver
        $itemCounts = array_fill(0, $totalDrivers, $locationsPerDriver);

        // Assign the remaining locations to the first driver
        for ($i = 0; $i < $remainingItems; $i++) {
            $itemCounts[$i]++;
        }

        $data = [];
        $endLocationIndex = 0;
        for ($i = 0; $i < $totalDrivers; $i++) {
            $data[$i][0] = $tpaPecuk; // Assign TPA Pecuk data to each driver

            for ($j = 1; $j <= $itemCounts[$i]; $j++) {
                $data[$i][$j] = $endLocation[$endLocationIndex];
                $endLocationIndex++;
            }
        }

        $locations = [];
        $totalJarakPerDriver = [];

        foreach ($data as $key => $item) {
            $totalJarak = 0;
            $totalLocations = count($item);

            for ($key2 = 0; $key2 < $totalLocations; $key2++) {
                $location = $item[$key2];

                $locations[$key][$key2] = [
                    'name' => $location['name'],
                    'lng' => $location['lng'],
                    'lat' => $location['lat'],
                ];

    // hitung jarak
                if ($key2 > 0) {
                    $jarakSebelumnya = $this->haversineDistance(
                        $item[$key2 - 1]['lat'],
                        $item[$key2 - 1]['lng'],
                        $location['lat'],
                        $location['lng']
                    );
                    $totalJarak += $jarakSebelumnya;
                }
            }

            // Calculate distance from the last location back to the first location (TPA Pecuk)
            $jarakTerakhir = $this->haversineDistance(
                $item[$totalLocations - 1]['lat'],
                $item[$totalLocations - 1]['lng'],
                $data[$key][0]['lat'],
                $data[$key][0]['lng']
            );
            $totalJarak += $jarakTerakhir;

            $totalJarakPerDriver[$key] = number_format($totalJarak, 3);
        }

        // Calculate total distance including the return trip from the last location to the first location
        $totalJarakKeseluruhan = array_sum($totalJarakPerDriver);

        $lines = [];
        foreach ($data as $key => $item) {
            foreach ($item as $key2 => $location) {
                $lines[$key][$key2] = [
                    'lng' => $location['lng'],
                    'lat' => $location['lat'],
                ];
            }
        }
// dd($totalJarakPerDriver);
        return view('rute.perdriver', [
            'locations' => $locations,
            'lines' => $lines,
            'tpaPecuk' => $tpaPecuk,
            'driver' => $driver,
            'totalJarakPerDriver' => $totalJarakPerDriver,
        ]);
    }


}
