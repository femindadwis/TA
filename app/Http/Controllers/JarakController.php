<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Lokasi, Jarak};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class JarakController extends Controller
{
    public function index()
    {
        $logic = $this->logic();
        $data = [
            'locations' => $logic['location'],
            'distances' => $logic['distance']
        ];
        return view('jarak.jarak', $data);
    }

    // public function form()
    // {
    //     return view('jarak.jarak_tambah');
    // }

    public function create()
    {
        $data = $this->logic();
        $locations = $data['location'];
        $distances = $data['distance'];

        $jarak = Jarak::whereIn('loc_1', $locations->pluck('name')->toArray())->count();
        foreach ($distances as $index => $distance) {
            $loc_1 = $locations->where('id', $index + 1)->first();
            foreach ($distance as $locationId => $value) {
                $loc_2 = $locations->where('id', $locationId)->first();

                $loc1Name = $loc_1 ? $loc_1['name'] : "";
                $loc2Name = $loc_2 ? $loc_2['name'] : "";
                $create = [
                    'loc_1' => $loc1Name,
                    'loc_2' => $loc2Name,
                    'distance' => $value
                ];

                // Cek apakah data sudah ada dalam database
                $existingData = Jarak::where('loc_1', $loc1Name)
                    ->where('loc_2', $loc2Name)
                    ->where('distance', $value)
                    ->first();

                if (!$existingData) {
                    Jarak::create($create);
                }
            }
        }

        return redirect('jarak/jarak');
    }



    private function logic()
    {
        $api_key = "AIzaSyB2Xd4GJtDxGPUI7nlMV-I99x5EQqYqhGc";

        // Mengambil semua data lokasi dari database
        $locations = Lokasi::all();

        // Membuat array alamat
        $origins = $destinations = [];
        foreach ($locations as $location) {
            $origins[] = "{$location->lat},{$location->lng}";
            $destinations[] = "{$location->lat},{$location->lng}";
        }

        // Mengubah tanda spasi menjadi '+' untuk URL
        $origins = str_replace(' ', '+', implode('|', $origins));
        $destinations = str_replace(' ', '+', implode('|', $destinations));


        // Membuat URL API Distance Matrix
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$origins}&destinations={$origins}&key={$api_key}";

        $response = Http::get($url);

        $distances = [];
        $distance_element = [];

        if ($response->ok()) {
            // Mendapatkan hasil dari response API
            $data = $response->json();

            $elements = collect($data['rows'])->pluck('elements')->toArray();

            foreach ($locations as $key => $location) {
                $distance = null;
                $distance_element[$key] = collect($elements[$key])->where('distance', '!=', null)
                    ->where('distance.text', '!=', '')
                    ->where('status', 'OK')
                    ->all();

                foreach ($distance_element[$key] as $index => $element) {
                    if ($element['status'] == 'OK' && $element['distance']['text'] != '') {
                        $distance = $element['distance']['value'] / 1000; // konversi meter ke kilometer
                        $distances[$index][$location->id] = $distance;

                    }
                }

            }

        }
        $data = [
            'location' => $locations,
            'distance' => $distances
        ];

        return $data;

    }
}
