<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;

class Jeniskendaraan extends Model
{
    use HasFactory;
    protected $table = "jeniskendaraan";

    protected $guarded = [''];

public function driver()
{
    return $this->hasMany(Driver::class);
}
}
