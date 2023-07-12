<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\{Driver, User, Driver_lokasi, Lokasi, Jarak};
use Illuminate\Support\Facades\Http;


class DriverController extends Controller
{
    public function index()
    {
        $driver = DB::table('driver')->get();
        $user = DB::table('users')->get();
        // mengirim data pegawai ke view index
        // return view('driver/driver',['driver' => $driver]);
        return view ('driver/driver', ['driver' => $driver], ['user' => $user]);
    }

    public function tambah()
    {
        $user = DB::table('users')->where('level', '3')->get();
        // memanggil view tambah
        return view('driver/driver_tambah', ['user' => $user]);

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
        ]);
        // alihkan halaman driver
        return redirect('/driver/driver');

    }

    public function edit($id)
    {
        $driver = DB::table('driver')->where('id', $id)->get();
        $user = DB::table('users')->get();

        return view('driver/driver_edit',['driver' => $driver], ['user' => $user]);
    }


    public function update(Request $request)
    {
        // update data driver

        DB::table('driver')->where('id',$request->id)->update([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'username' => $request->username,
            'alamat' => $request->alamat,
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

            $optimalRoute = $this->findOptimalRoute($locations, $jarak);
            $optimalRoutePSO = $this->findOptimalRoutePSO($locations, $jarak);


            //  $optimalRoute = $this->findOptimalRoute($locations, $jarak);
            //  $optimalRoute[] = $optimalRoute[0];

             // Menghitung total jarak tempuh
             $totalDistance = $this->calculateTotalDistance($optimalRoute, $jarak);
             $totaljarak = $this->calculateTotalDistance($optimalRoutePSO, $jarak);
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
private function calculateTotalDistance($route, $jarak)
{
     $totalDistance = 0;

    for ($i = 1; $i < count($route); $i++) {
        $loc1 = $route[$i - 1];
        $loc2 = $route[$i];
        $totalDistance += $jarak[$loc1][$loc2];
    }
  // Tambahkan jarak dari titik terakhir kembali ke TPA Pecuk
  $lastLocation = $route[count($route) - 1];
  $totalDistance += $jarak[$lastLocation][1]; // 1 adalah ID TPA Pecuk

    return $totalDistance;
}


private function findOptimalRoutePSO($locations, $jarak)
{
    mt_srand(42);
    $numLocations = $locations->count();
    $numParticles = 20;
    $numIterations = 100;

    // Inisialisasi partikel dan kecepatan
    $particles = [];

    $globalBest = [];
    $globalBestFitness = INF;

    // Inisialisasi partikel terbaik pada setiap partikel
    $particleBest = [];
    $particleBestFitness = [];

    for ($i = 0; $i < $numParticles; $i++) {
        $route = $locations->pluck('id')->toArray();
        shuffle($route);
        $particles[$i] = $route;

        $distance = 0;
        for ($j = 0; $j < $numLocations - 1; $j++) {
            $distance += $jarak[$route[$j]][$route[$j + 1]];
        }

        // Mengupdate partikel terbaik
        $particleBest[$i] = $route;
        $particleBestFitness[$i] = $distance;

        // Mengupdate nilai global terbaik
        if ($distance < $globalBestFitness) {
            $globalBest = $route;
            $globalBestFitness = $distance;
        }
    }

    // Iterasi PSO
    for ($iter = 0; $iter < $numIterations; $iter++) {
        for ($i = 0; $i < $numParticles; $i++) {
            $route = $particles[$i];
            $distance = 0;
            for ($j = 0; $j < $numLocations - 1; $j++) {
                $distance += $jarak[$route[$j]][$route[$j + 1]];
            }

            // Perbarui nilai kebugaran partikel terbaik dan nilai kebugaran global terbaik
            if ($distance < $particleBestFitness[$i]) {
                $particleBest[$i] = $route;
                $particleBestFitness[$i] = $distance;
            }
            if ($distance < $globalBestFitness) {
                $globalBest = $route;
                $globalBestFitness = $distance;
            }

            // Perbarui partikel dan kecepatan
            $particles[$i] = $route;
        }
    }

    // Pastikan urutan lokasi dimulai dari lokasi dengan ID 1
    $startIndex = array_search(1, $globalBest);
    $optimalRoutePSO = array_merge(
        array_slice($globalBest, $startIndex),
        array_slice($globalBest, 0, $startIndex)
    );
    dd($particles);
    return $optimalRoutePSO;
}
}

