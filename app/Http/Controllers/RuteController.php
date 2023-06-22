<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Jarak;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RuteController extends Controller
{
    public function index()
    {
        // Mendapatkan data lokasi dari database
        $locations = Lokasi::select('id', 'name', 'lat', 'lng')->get()->toArray();

        // Mendapatkan data jarak dari database
        $jarakData = Jarak::select('loc_1', 'loc_2', 'distance')->get()->toArray();

        // Mengonversi data jarak ke dalam bentuk matriks
        $jarak = [];
        foreach ($jarakData as $data) {
            $jarak[$data['loc_1']][$data['loc_2']] = $data['distance'];
        }


         $optimalRoute = $this->findOptimalRoute($locations, $jarak);

//  PRNYA MASIH BELUM ILANGIN ZOOM OTOMATIS
         // Menambahkan lokasi awal (start location) pada akhir rute
         $optimalRoute[] = $optimalRoute[0];

         // Menghitung total jarak tempuh
         $totalDistance = $this->calculateTotalDistance($optimalRoute, $jarak);

        return view('rute.rute_gmaps', compact('optimalRoute', 'locations', 'totalDistance'));
    }
    private function findOptimalRoute($locations, $jarak)
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

        $startingIndex = array_search($startingLocation, $locations);
        $remainingLocations = array_values(array_diff_key($locations, [$startingIndex => $startingLocation]));

        $findRoute([$startingLocation['id']], $remainingLocations, 0);

        return $optimalRoute;
    }

    private function calculateTotalDistance($route, $jarak)
    {
         $totalDistance = 0;

        for ($i = 1; $i < count($route); $i++) {
            $loc1 = $route[$i - 1];
            $loc2 = $route[$i];
            $totalDistance += $jarak[$loc1][$loc2];
        }

        return $totalDistance;
    }

}
