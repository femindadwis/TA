<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\{Driver, User, Driver_lokasi, Lokasi, Jarak};
use Illuminate\Support\Facades\Http;
use App\Models\Jeniskendaraan;

class RuteController extends Controller
{
    public function index()
    {
        $data = [
            "driver" => Driver::all(),
        ];
        return view('rute.rute', $data);
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

        $optimalRoute = $this->findOptimalRoute($locations, $jarak);
        $optimalRoutePSO = $this->findOptimalRoutePSO($locations, $jarak);

        // Menghitung total jarak tempuh
        $totalDistance = $this->calculateTotalDistances($optimalRoute, $jarak);
        $totaljarak = $this->calculateTotalDistances($optimalRoutePSO, $jarak);
        $data = [
            'driver' => $driver,
            'user' => User::all(),
            'locations' => $locations,
            'distances' => $distances,
            'driver_lokasi' => $driver_lokasi,
            'optimalRoute' => $optimalRoute,
            'optimalRoutePSO' => $optimalRoutePSO,
            'totalDistance' => $totalDistance,
            'totaljarak' => $totaljarak
        ];
        // dd($totaljarak); 
        return view('rute.rute_detail', $data);
    }

    public function rute()
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

        $optimalRoute = $this->findOptimalRoute($locations, $jarak);
        $optimalRoutePSO = $this->findOptimalRoutePSO($locations, $jarak);

        // Menghitung total jarak tempuh
        $totalDistance = $this->calculateTotalDistances($optimalRoute, $jarak);
        $totaljarak = $this->calculateTotalDistances($optimalRoutePSO, $jarak);
        $data = [
            'driver' => $driver,
            'user' => User::all(),
            'locations' => $locations,
            'distances' => $distances,
            'driver_lokasi' => $driver_lokasi,
            'optimalRoute' => $optimalRoute,
            'optimalRoutePSO' => $optimalRoutePSO,
            'totalDistance' => $totalDistance,
            'totaljarak' => $totaljarak
        ];
        // dd($totaljarak);
        return view('rute.rute_driver', $data);
    }

    // fungsi itung jarak mapbox api
    private function calculateDistances($locations)
    {
        $apiKey = 'pk.eyJ1Ijoicnl0b2RldiIsImEiOiJjbGtncDB3a3YwMXV3M2VvOHFqdmd2NWY4In0.pag9rpV51QYupsyPdSFfOw';
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
    public function findOptimalRoute($locations, $jarak)

    {
        // Ubah koleksi Eloquent $locations menjadi array
        $locationsArray = $locations->toArray();

        // Hitung jarak antara setiap pasang kota dan bangun matriks jarak
        $numLocations = count($locationsArray);
        $distanceMatrix = [];
        for ($i = 0; $i < $numLocations; $i++) {
            $distanceMatrix[$i] = [];
            for ($j = 0; $j < $numLocations; $j++) {
                $distanceMatrix[$i][$j] = $jarak[$locationsArray[$i]['id']][$locationsArray[$j]['id']];
            }
        }

        // KNN: Urutkan kota-kota berdasarkan jarak terdekat ke kota awal (id = 1)
        $startingLocationId = 1;
        $startingLocationIndex = array_search($startingLocationId, array_column($locationsArray, 'id'));
        $visited = array_fill(0, $numLocations, false);
        $visited[$startingLocationIndex] = true;
        $route = [$startingLocationIndex];

        for ($i = 1; $i < $numLocations; $i++) {
            $nearestLocationIndex = null;
            $nearestDistance = PHP_INT_MAX;

            for ($j = 0; $j < $numLocations; $j++) {
                if (!$visited[$j] && $distanceMatrix[$startingLocationIndex][$j] < $nearestDistance) {
                    $nearestDistance = $distanceMatrix[$startingLocationIndex][$j];
                    $nearestLocationIndex = $j;
                }
            }

            $route[] = $nearestLocationIndex;
            $visited[$nearestLocationIndex] = true;
            $startingLocationIndex = $nearestLocationIndex;
        }

        // Kembali ke kota awal untuk menyelesaikan rute
        $route[] = $startingLocationIndex;

        // Hapus lokasi awal dari rute (kota pertama telah berada di akhir rute)
        array_pop($route);

        // Ubah indeks menjadi id kota
        $optimalRoute = array_map(function ($index) use ($locationsArray) {
            return $locationsArray[$index]['id'];
        }, $route);

        return $optimalRoute;
    }




    public function findOptimalRoutePSO($locations, $jarak)
    {
        $numLokasi = count($locations);
        $numParticles = $this->factorial($numLokasi - 1);

        $maxIterations = 500;
        $maxExecutionTime = 2;


        $particles = [];
        $bestGlobalPosition = [];
        $bestGlobalFitness = PHP_INT_MAX;

        $bestInitialPosition = [1];
        foreach ($locations as $location) {
            if ($location['id'] != 1) {
                $bestInitialPosition[] = $location['id'];
            }
        }
        $bestGlobalPosition = $bestInitialPosition;
        $bestGlobalFitness = $this->calculateTotalDistances($bestInitialPosition, $jarak);

        for ($i = 0; $i < $numParticles; $i++) {
            $position = $bestInitialPosition;
            shuffle($position);
            $velocity = array_fill(0, $numLokasi - 1, 0);
            $bestPosition = $position;
            $bestFitness = $this->calculateTotalDistances($position, $jarak);

            $particles[] = compact('position', 'velocity', 'bestPosition', 'bestFitness');
        }

        $startTime = microtime(true);

        for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
            $currentTime = microtime(true);
            if ($currentTime - $startTime >= $maxExecutionTime) {
                break;
            }

            foreach ($particles as &$particle) {

                $cognitiveWeight = 1.0;
                $socialWeight = 1.0;
                $inertiaWeight = 0.6;

                for ($i = 1; $i < $numLokasi; $i++) {
                    $r1 = mt_rand() / mt_getrandmax();
                    $r2 = mt_rand() / mt_getrandmax();


                    $cognitiveComponent = $cognitiveWeight * $r1 * ($particle['bestPosition'][$i - 1] - $particle['position'][$i]);
                    $socialComponent = $socialWeight * $r2 * ($bestGlobalPosition[$i - 1] - $particle['position'][$i]);
                    $particle['velocity'][$i - 1] = $inertiaWeight * $particle['velocity'][$i - 1] + $cognitiveComponent + $socialComponent;
                }

                for ($i = 0; $i < $numLokasi - 1; $i++) {
                    $particle['velocity'][$i] = ($particle['velocity'][$i] >= 0.5) ? 1 : 0;
                }

                $position = $particle['position'];
                $velocity = $particle['velocity'];

                $newPosition = $position;

                for ($i = 0; $i < $numLokasi - 1; $i++) {

                    if ($velocity[$i] === 1) {
                        $temp = $newPosition[$i];
                        $newPosition[$i] = $newPosition[$i + 1];
                        $newPosition[$i + 1] = $temp;
                    }
                }


                $totalJarak = $this->calculateTotalDistances($newPosition, $jarak);
                if ($totalJarak < $particle['bestFitness']) {
                    $particle['bestFitness'] = $totalJarak;
                    $particle['bestPosition'] = $newPosition;
                }


                if ($totalJarak < $bestGlobalFitness) {
                    $bestGlobalFitness = $totalJarak;
                    $bestGlobalPosition = $newPosition;
                }


                $particle['velocity'] = $velocity;
            }
        }


        $bestParticleIndex = 0;
        for ($i = 1; $i < $numParticles; $i++) {
            if ($particles[$i]['bestFitness'] < $particles[$bestParticleIndex]['bestFitness']) {
                $bestParticleIndex = $i;
            }
        }

        $bestRoute = $particles[$bestParticleIndex]['bestPosition'];

        $index = array_search(1, $bestRoute);
        $finalRoute = array_merge(array_slice($bestRoute, $index), array_slice($bestRoute, 0, $index));
        // dd($bestFitness);
        return $finalRoute;
    }


    function factorial($n)
    {
        if ($n <= 1) {
            return 1;
        } else {
            return $n * $this->factorial($n - 1);
        }
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
