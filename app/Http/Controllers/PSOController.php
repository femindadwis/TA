<?php

namespace App\Http\Controllers;

use App\Models\PSO;
use App\Models\Jarak;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PSOController extends Controller
{
    public function index()
    {
        // Ambil data lokasi dari database
        $locations = Lokasi::all();

        // Ambil data jarak antara lokasi dari database
        $distances = $this->getDistanceMatrix();

        // Cari rute teroptimal menggunakan algoritma PSO
        $optimalRoute = $this->findOptimalRoute($locations, $distances);

        // Simpan hasil optimasi ke dalam database
        $this->saveOptimalRoute($optimalRoute);

        // Ambil data detail rute teroptimal
        $routeDetails = $this->getRouteDetails($optimalRoute, $distances);

        // Kirim data ke tampilan
        return view('rute.rute_pso', compact('optimalRoute', 'locations', 'routeDetails'));
    }

    private function getDistanceMatrix()
    {
        // Ambil data jarak antara lokasi dari database
        $distances = Jarak::select('loc_1', 'loc_2', 'distance')->get();

        // Ubah format data jarak menjadi matriks
        $matrix = [];
        foreach ($distances as $distance) {
            $matrix[$distance->loc_1][$distance->loc_2] = $distance->distance;
        }

        return $matrix;
    }
    private function findOptimalRoute($locations, $distances)
    {
        // Inisialisasi parameter PSO
        $particleCount = 20; // Jumlah partikel
        $maxIteration = 100; // Jumlah iterasi
        $c1 = 2; // Konstanta percepatan kognitif
        $c2 = 2; // Konstanta percepatan sosial
        $w = 0.9; // Inersia

        $locCount = count($locations); // Jumlah lokasi

        // Inisialisasi partikel
        $particles = [];
        for ($i = 0; $i < $particleCount; $i++) {
            $particle = [
                'route' => range(2, $locCount), // Exclude location id = 1
                'pBest' => [], // Rute terbaik partikel
                'pBestCost' => INF, // Biaya terbaik partikel
                'velocity' => [], // Kecepatan partikel
            ];
            shuffle($particle['route']);
            $particle['route'] = array_merge([1], $particle['route'], [1]); // Add location id = 1 at the beginning and end
            $particles[] = $particle;
        }

        // Inisialisasi rute global terbaik
        $gBest = $particles[0]['route'];
        $gBestCost = $this->calculateRouteCost($gBest, $distances);

        // Iterasi PSO
        for ($iter = 0; $iter < $maxIteration; $iter++) {
            foreach ($particles as &$particle) {
                // Evaluasi biaya rute partikel
                $cost = $this->calculateRouteCost($particle['route'], $distances);

                // Memperbarui pBest partikel
                if ($cost < $particle['pBestCost']) {

                    $particle['pBest'] = $particle['route'];
                    $particle['pBestCost'] = $cost;
                }

                // Memperbarui gBest jika diperlukan
                if ($cost < $gBestCost) {
                    $gBest = $particle['route'];
                    $gBestCost = $cost;
                }

                // Menghitung kecepatan partikel
                $newVelocity = [];
                foreach ($particle['velocity'] as $index => $velocity) {
                    $r1 = mt_rand() / mt_getrandmax();
                    $r2 = mt_rand() / mt_getrandmax();
                    $newVelocity[$index] = $w * $velocity
                        + $c1 * $r1 * ($particle['pBest'][$index] - $particle['route'][$index])
                        + $c2 * $r2 * ($gBest[$index] - $particle['route'][$index]);
                }
                $particle['velocity'] = $newVelocity;

                // Memperbarui rute partikel berdasarkan kecepatan
                $particle['route'] = $this->updateRouteByVelocity($particle['route'], $particle['velocity']);
            }
        }

        return $gBest;
    }

    private function calculateRouteCost($route, $distances)
    {
        $cost = 0;
        $locCount = count($route);
        for ($i = 0; $i < $locCount - 1; $i++) {
            $loc1 = $route[$i];
            $loc2 = $route[$i + 1];
            $cost += $distances[$loc1][$loc2];
        }
        return $cost;
    }

    private function updateRouteByVelocity($route, $velocity)
    {
        $newRoute = $route;
        $locCount = count($route);
        $velocityCount = count($velocity); // Get the length of the velocity array

        // Adjust the velocity array length if necessary
        if ($velocityCount < $locCount) {
            $velocity = array_pad($velocity, $locCount, 0);
        } elseif ($velocityCount > $locCount) {
            $velocity = array_slice($velocity, 0, $locCount);
        }

        for ($i = 0; $i < $locCount; $i++) {
            $newIndex = ($route[$i] - 1 + round($velocity[$i])) % $locCount;
            $newRoute[$i] = $newIndex + 1;
        }
        return $newRoute;
    }
    private function saveOptimalRoute($route)
    {
        // Konversi array rute ke dalam format string
        $routeString = implode(',', $route);

        // Periksa apakah data rute sudah ada sebelumnya
        $existingRoute = PSO::where('route', $routeString)->first();

        // Jika data rute belum ada, simpan ke dalam database
        if (!$existingRoute) {
            PSO::create([
                'route' => $routeString,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }


    private function getRouteDetails($route, $distances)
    {
        $routeDetails = [];
        $locCount = count($route);
        for ($i = 0; $i < $locCount - 1; $i++) {
            $loc1 = $route[$i];
            $loc2 = $route[$i + 1];
            $distance = $distances[$loc1][$loc2];
            $routeDetails[] = [
                'loc1' => $loc1,
                'loc2' => $loc2,
                'distance' => $distance,
            ];
        }
        return $routeDetails;
    }
}
