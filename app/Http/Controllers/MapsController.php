<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MapsController extends Controller
{
    public function index()
    {
        $locations = DB::table('lokasi')->get();
        return view ('maps.maps',['locations'=>$locations]);
    }

}
