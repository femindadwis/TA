<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lokasi;
class Jarak extends Model
{
    use HasFactory;
    protected $table = 'jaraks';
    protected $fillable = [
        'loc_1',
        'loc_2',
        'distance',
    ];
    public function lokasi1()
    {
        return $this->belongsTo(Lokasi::class, 'loc_1');
    }

    public function lokasi2()
    {
        return $this->belongsTo(Lokasi::class, 'loc_2');
    }

}
