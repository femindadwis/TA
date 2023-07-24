<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Jeniskendaraan extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = "jeniskendaraan";

    protected $fillable = [
        'jenis_kendaraan',
    ];
public function driver()
{
    return $this->hasMany(Driver::class);
}
}
