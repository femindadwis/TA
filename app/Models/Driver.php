<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Jeniskendaraan;
use App\Models\Route;

class Driver extends Model
{
    use HasFactory;
    protected $table = 'driver';
    protected $fillable = [
        'user_id',
        'username',
        'alamat',
        'no_polisi',
        'no_telepon',
        'jeniskendaraan_id',


    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
public function jeniskendaraan()
{
    return $this->belongsTo(Jeniskendaraan::class);
}
public function route()
    {
        return $this->hasMany(Route::class, 'driver_id');
    }

}
