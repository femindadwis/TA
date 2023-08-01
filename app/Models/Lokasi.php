<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Models\Jarak;
use App\Models\Driver_lokasi;
use App\Models\Route;
class Lokasi extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'lokasi';
    protected $fillable = [
        'name',
        'alamat',
        'lng',
        'lat',
        'foto',
    ];
    public function jarak1()
    {
        return $this->hasMany(Jarak::class, 'loc_1');
    }

    public function jarak2()
    {
        return $this->hasMany(Jarak::class, 'loc_2');
    }
    public function driver_lokasis()
    {
        return $this->hasMany(Driver_lokasi::class);
    }

    public function route()
    {
        return $this->hasMany(Route::class, 'lokasi_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($lokasi) {
            $lokasi->jarak1()->delete();
            $lokasi->jarak2()->delete();
        });
    }
}
