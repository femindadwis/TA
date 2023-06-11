<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lokasi;
class Driver_lokasi extends Model
{
    use HasFactory;
    protected $table = "driver_lokasis";

    protected $guarded = [''];
    public function users()
    {
        return $this->belongsTo("App\Models\User", "user_id", "id");
    }
    public function lokasi()
    {
        return $this->belongsTo("App\Models\Lokasi", "lokasi_id", "id");
    }

}
