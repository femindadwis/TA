<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PSOController extends Controller
{
        public function index()
        {

            return view('rute.rute_pso');

        }


        public function data(Request $request, $id)
        {
            //data preparation
            $user = auth()->user()->level;
            // data tambahan
            $tpaPecuk = Lokasi::find(1)->toArray();
            // dd($tpaPecuk['lat']);

            //data tambahan

            $endLocation = Lokasi::where('id', '!=', 1)->get()->toArray();

            $startLocations = [
                [
                    'name' => $tpaPecuk['name'],
                    'lat' => $tpaPecuk['lat'],
                    'lng' => $tpaPecuk['lng'],
                ],

                [
                    'name' => $tpaPecuk['name'],
                    'lat' => $tpaPecuk['lat'],
                    'lng' => $tpaPecuk['lng'],
                ],


                [
                    'name' => $tpaPecuk['name'],
                    'lat' => $tpaPecuk['lat'],
                    'lng' => $tpaPecuk['lng'],
                ],
            ];
            //data preparation

            //pembagian data ke setiap driver
            $totalEndLocations = count($endLocation); // Jumlah total lokasi yang akan dibagi
            $totalDrivers = count($startLocations); // Jumlah total driver

            $locationsPerDriver = floor($totalEndLocations / $totalDrivers); // Jumlah lokasi per driver (pembulatan ke bawah)

            $remainingItems = $totalEndLocations % $totalDrivers; // Sisa lokasi setelah pembagian

            $itemCounts = array_fill(0, $totalDrivers, $locationsPerDriver);

            // Memasukkan sisa lokasi ke driver pertama
            for ($i = 0; $i < $remainingItems; $i++) {
                $itemCounts[$i]++;
            }

            // Menyusun hasil pembagian ke dalam array bertujuan untuk membagi lokasi terdekat ke setiap driver
            for ($i = 0; $i < $totalDrivers; $i++) {
                $outputArray[] = array(
                    'driver' => ($i + 1),
                    'itemCount' => $itemCounts[$i]
                );
            }
            //pembagian data ke setiap driver


            // dd($outputArray[1]['itemCount']);
            // Iterasi PSO
            $data = array();
            for ($i = 0; $i < count($startLocations); $i++) {
                // $data[$i][0] = $startLocations[$i]; //memasukan data driver
                $data[$i][0] = $tpaPecuk; //memasukan lokasi awal tpa pecuk ke setiap driver

                for ($j = 1; $j <= $outputArray[$i]['itemCount']; $j++) {
                    // $data[$i][$j] = $startLocations[$i];

                    // pbest di persingkat dengan menggunakan global best
                    // ketika mendapatkan  global best data
                    // dalam konteks ini mengambil global best langsung kita masukan ke dalam variable data berupa array
                    // untuk mempersingkat update posisi partikel Global best
                    $data[$i][$j] = $this->getGlobalBest($startLocations[$i]['lat'], $startLocations[$i]['lng'], $endLocation);
                    // dd($data);


                    // menghapus global best yang sudah di ambil agar bertujuan Global best yang sudah di masukan ke dalam array
                    // berganti dengan global best selanjutnya
                    $key = array_search($data[$i][$j], $endLocation);
                    if ($key !== false) {
                        unset($endLocation[$key]);
                    }
                }

                // memasukan Tpa pecuk di akhir lokasi
                $data[$i][$outputArray[$i]['itemCount']] = $tpaPecuk; //memasukan data tpa pecuk ke setiap driver

            }

            // dd($data);
            // solusi optimal didapat


            //solusi optimal dilakukan penyesuaian data agar bisa di tampilkan sebagai mark lokasi
            $locations = [];

            foreach ($data  as $key => $item) {
                foreach ($item as $key2 => $location) {

                    $locations[$key][$key2] = [

                        $location["name"],
                        (float)$location["lat"],
                        (float)$location["lng"],
                    ];
                }
            }
            // dd($locations);
            //solusi optimal dilakukan penyesuaian data agar bisa di tampilkan sebagai mark lokasi


            //solusi optimal dilakukan penyesuaian data agar bisa di tampilkan sebagai line

            $lines = [];
            foreach ($data as $key => $item) {

                foreach ($item as $key2 => $location) {
                    $lines[$key][$key2] = [
                        "lng" => (float)$location["lng"],
                        "lat" => (float)$location["lat"],
                    ];
                }
            }
            //solusi optimal dilakukan penyesuaian  data agar bisa di tampilkan sebagai l
            $driver = $id;

            // return view('maps.maps', ['driver' => $driver, 'data' => $data, 'locations' => $locations, 'lines' => $lines]);
            return response()->json(['driver' => $driver, 'data' => $data, 'locations' => $locations, 'lines' => $lines]);
        }



        function getGlobalBest($startLat, $startLng, $locations)
        {
            // Inisialisasi variabel untuk lokasi terdekat dan jarak terpendek atau globalbest
            $bestLocation = null;
            $bestDistance = null;

            // Melakukan perulangan untuk setiap lokasi dalam array $locations
            foreach ($locations as $location) {
                $lat = $location['lat'];
                $lng = $location['lng'];

                // Menghitung jarak menggunakan fungsi haversineDistance
                $distance = $this->haversineDistance($startLat, $startLng, $lat, $lng);

                // Memeriksa apakah jarak terdekat belum diinisialisasi atau jarak saat ini lebih kecil
                if ($bestDistance === null || $distance < $bestDistance) {
                    // Memperbarui lokasi terdekat dan jarak terpendek /globalbest
                    $bestLocation = $location;
                    $bestDistance = $distance;
                }
            }
            // Mengembalikan lokasi terdekat
            return $bestLocation;
        }


        function haversineDistance($lat1, $lng1, $lat2, $lng2)
        {
            // penggunaan inisialisasi paramter pso
            $earthRadius = 6371; // merupakan radius bumi dalam kilometer.


            $deltaLat = deg2rad($lat2 - $lat1);
            $deltaLng = deg2rad($lng2 - $lng1);

            $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($deltaLng / 2) * sin($deltaLng / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $distance = $earthRadius * $c;

            return $distance;
            // penggunaan inisialisasi paramter pso

        }
    }
