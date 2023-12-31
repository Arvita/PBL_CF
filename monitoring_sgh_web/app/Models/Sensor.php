<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;
    protected $fillable = [
        'sensor_name',
    ];
    public function DetailSensor()
    {
        return $this->hasMany(DetailSensor::class, 'id_sensors');
    }
}

?>