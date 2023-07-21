<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\{Driver, User, Driver_lokasi, Lokasi, Jarak};
use Illuminate\Support\Facades\Http;
use App\Models\Jeniskendaraan;


class DriverController extends Controller
{
    public function index()
    {
        $data = [
            "driver" => Driver::all(),
            "user" => User::all(),
            "jeniskendaraan" => Jeniskendaraan::all()
        ];
        // dd($data);
        return view('driver.driver', $data);

    }

    public function tambah()
    {
        $user = DB::table('users')->where('level', '3')->get();
        $jenis_kendaraan = DB::table('jeniskendaraan')->get();
        // memanggil view tambah
        return view('driver/driver_tambah', ['user' => $user], ['jenis_kendaraan' => $jenis_kendaraan]);

    }

    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        // insert data ke table driver
        DB::table('driver')->insert([
            'user_id' => $request->user_id,
            'name' => $user['name'],
            'username' => $request->username,
            'alamat' => $request->alamat,
            'no_polisi' => $request->no_polisi,
            'no_telepon' => $request->no_telepon,
            'jeniskendaraan_id' => $request->jeniskendaraan_id,
        ]);
        // alihkan halaman driver
        return redirect('/driver/driver');

    }

    public function edit($id)
    {
        $driver = DB::table('driver')->where('id', $id)->get();
        $user = DB::table('users')->get();
        $jenis_kendaraan = DB::table('jeniskendaraan')->get();
        return view('driver/driver_edit',['driver' => $driver], ['user' => $user], ['jenis_kendaraan' => $jenis_kendaraan]);
    }


    public function update(Request $request)
    {
        // update data driver

        DB::table('driver')->where('id',$request->id)->update([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'username' => $request->username,
            'alamat' => $request->alamat,
            'no_polisi' => $request->no_polisi,
            'no_telepon' => $request->no_telepon,
            'jeniskendaraan_id' => $request->jeniskendaraan_id,
        ]);
        // alihkan halaman ke halaman driver
        return redirect('/driver/driver')->with('success', 'driver Telah di Ubah!');
    }

    public function hapus($id)
    {
        // menghapus data driver berdasarkan id yang dipilih
        DB::table('driver')->where('id',$id)->delete();

        // alihkan halaman ke halaman driver
        return redirect('/driver/driver');

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
                    ['distance' => $distance]  );
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


            //  $optimalRoute = $this->findOptimalRoute($locations, $jarak);
            //  $optimalRoute[] = $optimalRoute[0];

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
            'totalDistance' =>  $totalDistance,
            'totaljarak' => $totaljarak
        ];
// dd($totaljarak);
        return view('driver.driver_detail', $data);
    }

// fungsi itung jarak
private function calculateDistances($locations)
{
    $apiKey = 'AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg';
    $distances = [];

    foreach ($locations as $location) {
        $origins = $destinations = [];

        foreach ($locations as $otherLocation) {
            $origins[] = $location->lat . ',' . $location->lng;
            $destinations[] = $otherLocation->lat . ',' . $otherLocation->lng;
        }

        $origins = implode('|', $origins);
        $destinations = implode('|', $destinations);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$origins}&destinations={$destinations}&key={$apiKey}";

        $response = Http::get($url);

        if ($response->ok()) {
            $data = $response->json();

            foreach ($data['rows'] as $i => $row) {
                foreach ($row['elements'] as $j => $element) {
                    if ($element['status'] == 'OK' && isset($element['distance']['value'])) {
                        $distances[$location->id][$locations[$j]->id] = $element['distance']['value'] / 1000;
                    }
                }
            }
        }
    }

    return $distances;
}private function findOptimalRoute($locations, $jarak)
{
    $numLocations = count($locations);
    $optimalRoute = [];
    $minDistance = INF;

    // Fungsi rekursif untuk mencari rute terbaik secara berulang
    $findRoute = function ($currentRoute, $remainingLocations, $currentDistance) use (
        &$optimalRoute,
        &$minDistance,
        $jarak,
        &$findRoute,
    ) {
        if (empty($remainingLocations)) {
            // Jika tidak ada lokasi yang tersisa, periksa apakah rute ini lebih optimal
            if ($currentDistance < $minDistance) {
                $minDistance = $currentDistance;
                $optimalRoute = $currentRoute;
            }
        } else {
            foreach ($remainingLocations as $key => $location) {
                $newRoute = array_merge($currentRoute, [$location['id']]);
                $newRemainingLocations = array_values(array_diff_key($remainingLocations, [$key => $location]));
                $newDistance = $currentDistance + $jarak[$currentRoute[count($currentRoute) - 1]][$location['id']];

                $findRoute($newRoute, $newRemainingLocations, $newDistance);
            }
        }
    };

    // Mulai pencarian rute terbaik dari setiap lokasi
    $startingLocation = null;
    foreach ($locations as $key => $location) {
        if ($location['id'] == 1) {
            $startingLocation = $location;
            break;
        }
    }

    $startingIndex = array_search($startingLocation, $locations->toArray());
    $remainingLocations = array_values(array_diff_key($locations->toArray(), [$startingIndex => $startingLocation]));


    $findRoute([$startingLocation['id']], $remainingLocations, 0);

    return $optimalRoute;
}

// fungsi itung total jarak
// private function calculateTotalDistance($route, $jarak)
// {
//     $totalDistance = 0;

//     if (empty($route)) {
//         return $totalDistance;
//     }

//     for ($i = 1; $i < count($route); $i++) {
//         $loc1 = $route[$i - 1];
//         $loc2 = $route[$i];
//         $totalDistance += $jarak[$loc1][$loc2];
//     }

//     // Tambahkan jarak dari titik terakhir kembali ke TPA Pecuk
//     $lastLocation = $route[count($route) - 1];
//     $totalDistance += $jarak[$lastLocation][1];
// // dd($jarak);
//     return $totalDistance;
// }


function findOptimalRoutePSO($locations, $jarak)
{
    $numLokasi = count($locations);
    $numParticles = $this->factorial($numLokasi - 1);
    $maxIterations = 100;

    // Initialize particles
    $particles = [];
    $bestGlobalPosition = [];
    $bestGlobalFitness = PHP_INT_MAX;

    // Generate unique random positions for particles
    for ($i = 0; $i < $numParticles; $i++) {
        $position = [1]; // Dimulai dari lokasi dengan id = 1
        $velocity = array_fill(0, $numLokasi, 0);
        $bestPosition = null;
        $bestFitness = PHP_INT_MAX;

        $lokasiIndices = range(0, $numLokasi - 1);
        shuffle($lokasiIndices);

        // Menambahkan lokasi yang dipilih ke dalam posisi partikel
        foreach ($lokasiIndices as $index) {
            $lokasiId = $locations[$index]['id'];

            if (!in_array($lokasiId, $position)) {
                $position[] = $lokasiId;
            }
        }

        // Cek apakah posisi partikel sudah ada sebelumnya
        $isDuplicate = false;
        foreach ($particles as $particle) {
            if ($particle['position'] == $position) {
                $isDuplicate = true;
                break;
            }
        }

        // Jika posisi partikel sudah ada sebelumnya, lakukan generate ulang posisi
        while ($isDuplicate) {
            shuffle($lokasiIndices);

            $position = [1];
            foreach ($lokasiIndices as $index) {
                $lokasiId = $locations[$index]['id'];

                if (!in_array($lokasiId, $position)) {
                    $position[] = $lokasiId;
                }
            }

            $isDuplicate = false;
            foreach ($particles as $particle) {
                if ($particle['position'] == $position) {
                    $isDuplicate = true;
                    break;
                }
            }
        }

        // Calculate total distance for the current particle
        $totalJarak = $this->calculateTotalDistances($position, $jarak);

        // Update best position and best fitness
        if ($totalJarak < $bestFitness) {
            $bestFitness = $totalJarak;
            $bestPosition = $position;
        }

        $particles[] = compact('position', 'velocity', 'bestPosition', 'bestFitness');
    }
// dd($particles);
    $bestGlobalPosition = $particles[0]['position'];
    for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
        foreach ($particles as &$particle) {
            // Update velocity
            $cognitiveWeight = 1.0;
            $socialWeight = 1.0;
            $inertiaWeight = 0.8;

            for ($i = 1; $i < $numLokasi; $i++) {
                $r1 = mt_rand() / mt_getrandmax();
                $r2 = mt_rand() / mt_getrandmax();

                $cognitiveComponent = $cognitiveWeight * $r1 * ($particle['bestPosition'][$i] - $particle['position'][$i]);
                $socialComponent = $socialWeight * $r2 * ($bestGlobalPosition[$i] - $particle['position'][$i]);

                $particle['velocity'][$i] = $inertiaWeight * $particle['velocity'][$i] + $cognitiveComponent + $socialComponent;
            }

            // Update position
            $position = $particle['position'];
            $velocity = $particle['velocity'];

            // urutkan lokasi dari kecepatan
            array_multisort($velocity, $position);

            // Reconstruct the route based on the sorted positions
            $route = array_map(function($index) use ($position) {
                return $position[$index];
            }, array_keys($position));

            // Evaluate fitness for the new position
            $totalJarak = $this->calculateTotalDistances($route, $jarak);

            // Update best position and best fitness for the particle
            if ($totalJarak < $particle['bestFitness']) {
                $particle['bestFitness'] = $totalJarak;
                $particle['bestPosition'] = $route;
            }

            // Update best global position and best global fitness
            if ($totalJarak < $bestGlobalFitness) {
                $bestGlobalFitness = $totalJarak;
                $bestGlobalPosition = $route;
            }
        }

    }
    // dd($route);

        // Filter best global position to start from location with id = 1
        $index = array_search(1, $bestGlobalPosition);
        $bestGlobalPosition = array_merge(array_slice($bestGlobalPosition, $index), array_slice($bestGlobalPosition, 0, $index));
    // dd($bestGlobalFitness);

return $bestGlobalPosition;
}
// Fungsi untuk menghitung faktorial
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
                // Handle ketika $jarak[$titik1][$titik2] tidak ada
                // Misalnya, lakukan penanganan kesalahan atau berikan nilai default
                $totalJarak += 0; // Nilai default jika elemen tidak ada
            }
        } else {
            // Handle ketika $rute[$i] atau $rute[$i + 1] tidak ada
            // Misalnya, lakukan penanganan kesalahan atau berikan nilai default
            $totalJarak += 0; // Nilai default jika elemen tidak ada
        }
    }

    // Tambahkan jarak kembali ke titik awal
    $titikAwal = $rute[0];
    $titikAkhir = $rute[$jumlahTitik - 1];
    if (isset($jarak[$titikAkhir][$titikAwal])) {
        $totalJarak += $jarak[$titikAkhir][$titikAwal];
    } else {
        // Handle ketika $jarak[$titikAkhir][$titikAwal] tidak ada
        // Misalnya, lakukan penanganan kesalahan atau berikan nilai default
        $totalJarak += 0; // Nilai default jika elemen tidak ada
    }

    return $totalJarak;
}


}
