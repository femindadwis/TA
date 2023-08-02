<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;

class Routenn extends Model
{
    use HasFactory;
    protected $table = 'routenn';
    protected $fillable = [
        'driver_id',
        'urutan',
        'jarak',
    ];

public function driver()
{
    return $this->belongsTo(Driver::class);
}
}
