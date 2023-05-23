<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Driver extends Model
{
    use HasFactory;
    protected $table = 'driver';
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'alamat',


    ];
}
