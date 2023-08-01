<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;
use App\Models\Lokasi;

class Route extends Model
{
    use HasFactory;
    protected $table = 'route';
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
