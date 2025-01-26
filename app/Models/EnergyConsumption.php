<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnergyConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'carbon_footprint_id',
        'electricity_usage',
        'number_of_employees',
    ];

    public function energyConsumptionSources(): HasMany
    {
        return $this->hasMany(EnergyConsumptionSource::class, 'energy_consumption_id');
    }
}
