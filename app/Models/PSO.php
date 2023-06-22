<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSO extends Model
{
    protected $table = 'optimal_routes';
    protected $fillable = ['route'];
}
