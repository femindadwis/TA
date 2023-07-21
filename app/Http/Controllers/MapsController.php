<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Driver_lokasi;
use App\Models\User;
use App\Models\Lokasi;

class MapsController extends Controller
{
    public function index()
    {
        $locations = DB::table('lokasi')->get();
        // Ambil data lokasi_driver dari tabel 'lokasi_driver'
        $driverLocations = DB::table('driver_lokasis')
        ->join('lokasi', 'driver_lokasis.lokasi_id', '=', 'lokasi.id')
        ->join('users', 'driver_lokasis.user_id', '=', 'users.id') // Join ke tabel 'users' berdasarkan kolom 'user_id'
        ->select('driver_lokasis.user_id', 'lokasi.lat', 'lokasi.lng', 'lokasi.name', 'users.name as driver_name') // Ambil juga kolom 'name' dari tabel 'users' sebagai 'driver_name'
        ->get();
        // Buat array untuk menyimpan lokasi dan warna marker
        $lokasi = array();

        // Loop melalui data lokasi_driver
        foreach ($driverLocations as $driverLocation) {
            // Menghasilkan warna acak berdasarkan nama user, tetapi tetap konsisten
            $color = $this->generateRandomColor($driverLocation->user_id);

            // Ambil data lokasi dari tabel
            $latitude = $driverLocation->lat;
            $longitude = $driverLocation->lng;
            $namaLokasi = $driverLocation->name;
            $namaDriver = $driverLocation->driver_name;
            // Tambahkan lokasi dan warna marker ke dalam array
            $lokasi[] = array(
                'lat' => $latitude,
                'lng' => $longitude,
                'color' => $color,
                'user_id' => $driverLocation->user_id,
                'name' => $namaLokasi,
                'driver_name' => $namaDriver,
            );
        }

        return view('maps.maps', ['locations' => $locations], ['lokasi' => $lokasi]);
    }
    private function generateRandomColor($userId)
    {
         // Gunakan seed berdasarkan ID driver untuk memastikan warna yang konsisten
    srand($userId);

    // Mendapatkan komponen warna R (Red), G (Green), dan B (Blue)
    $red = rand(150, 255); // Komponen Red (merah) memiliki nilai acak antara 150 hingga 255
    $green = rand(0, 200); // Komponen Green (hijau) memiliki nilai acak antara 0 hingga 200
    $blue = rand(0, 150); // Komponen Blue (biru) memiliki nilai acak antara 0 hingga 150

    // Format warna acak dalam format heksadesimal (#RRGGBB)
    $color = sprintf('#%02X%02X%02X', $red, $green, $blue);

    // Kembalikan warna acak
    return $color;


    }
}
