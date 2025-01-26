<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyConsumptionSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'energy_consumption_id',
        'energy_source_id',
        'unit_id',
        'value'
    ];

    public function energySource()
    {
        return $this->belongsTo(EnergySource::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
