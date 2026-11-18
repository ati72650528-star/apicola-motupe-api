<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorColmena extends Model
{
    protected $table = 'sensor_colmenas';
    protected $primaryKey = 'id_sensor';
    protected $fillable = ['apiario', 'temperatura', 'humedad', 'mensaje_alerta'];
}