<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnergySource extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug'
    ];

    public function unitFactors(): HasMany
    {
        return $this->hasMany(EnergyUnitFactor::class, 'energy_source_id')->with('unit');
    }
}
