<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class RuteController extends Controller
{
    public function index()
    {
        $locations = DB::table('lokasi')->get();
        return view ('rute.rute_gmaps',['locations'=>$locations]);
    }

}
