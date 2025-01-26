<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarTransportationConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_type_id',
        'annual_mileage',
        'average_consumption',
        'emission_transportation_id'
    ];
}
