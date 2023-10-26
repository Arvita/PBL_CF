<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSensor extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_sensors','temp','humidity'
    ];
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'id_sensors');
    }
}
