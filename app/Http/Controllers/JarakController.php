<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;
use App\Models\Jarak;
use Illuminate\Support\Facades\Http;

class JarakController extends Controller
{
    public function index()
    {
        $locations = Lokasi::all();
        $distances = $this->calculateDistances($locations);

        foreach ($distances as $loc1Id => $distancesToOther) {
            foreach ($distancesToOther as $loc2Id => $distance) {
                Jarak::updateOrCreate(
                    ['loc_1' => $loc1Id, 'loc_2' => $loc2Id],
                    ['distance' => $distance]
                );
            }
        }

        return view('jarak.jarak', compact('locations', 'distances'));
    }

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
    }
}
