<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmissionTransportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled_mode',
        'carbon_footprint_id',
        'flight_very_long_max',
        'flight_very_long_min',
        'flight_long_max',
        'flight_long_min',
        'flight_medium_max',
        'flight_medium_min',
        'flight_short_max',
        'flight_short_min',
    ];
}
