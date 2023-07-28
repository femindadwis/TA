<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Jeniskendaraan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Models\User;


use DB;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            "user" => User::count(),
            "driver" => Driver::count(),
            "lokasi" => Lokasi::count(),
            "jeniskendaraan" => Jeniskendaraan::count()
         ];
        return view('dashboard', $data);
    }
}
