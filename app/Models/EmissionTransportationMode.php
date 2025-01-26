<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmissionTransportationMode extends Model
{
    use HasFactory;

    protected $fillable = [
        'transportation_mode_id',
        'emission_cycle_id',
        'value',
        'emission_transportation_id',
    ];
}
