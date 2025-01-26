<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarbonFootprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'start_date',
        'end_date',
        'country_id',
        'energy_emission',
        'transportation_emission',
        'lifestyle_emission',
        'total_emission',
        'month_from',
        'month_to'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return HasOne
     */
    public function energyConsumption(): HasOne
    {
        return $this->hasOne(EnergyConsumption::class, 'carbon_footprint_id');
    }

    /**
     * @param $query
     * @param array $searchData
     * @return mixed
     */
    public function scopeBySearch($query, array $searchData)
    {
        $search = $searchData['search'] ?? '';
        $from = $searchData['from'] ?? '';
        $to = $searchData['to'] ?? '';
        return $query->where(function ($query) use ($from, $to, $search) {

            if (!empty($from) && !empty($to)) $query->whereDate('carbon_footprints.created_at', '>=', $from)
                  ->whereDate('carbon_footprints.created_at', '<=', $to);
    
            if (!empty($search)) $query->orWhere('name', 'LIKE', "%{$search}%");

        })->orderBy('carbon_footprints.created_at', 'desc');
    }

    /**
     * @return HasOne
     */
    public function transportationEmission(): HasOne
    {
        return $this->hasOne(EmissionTransportation::class, 'carbon_footprint_id');
    }

    public function lifestyleEmission(): HasOne
    {
        return $this->hasOne(EmissionLifestyle::class, 'carbon_footprint_id');
    }



}

