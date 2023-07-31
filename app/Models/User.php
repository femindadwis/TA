<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Lokasi;
use App\Models\Driver;
use App\Models\Admin;
use App\Models\Driver_lokasi;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'level',

    ];
    public function driver_lokasis()
    {
        return $this->hasMany(Driver_lokasi::class);
    }

    public function driver()
    {
        return $this->hasMany(Driver::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class, 'user_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'username_verified_at' => 'datetime',
    // ];
}
