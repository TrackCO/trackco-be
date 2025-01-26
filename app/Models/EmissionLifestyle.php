<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmissionLifestyle extends Model
{
    use HasFactory;

    protected $fillable = [
        'carbon_footprint_id',
        'currency',
        'period',
        'paper_products_spending',
        'it_equipment_spending',
        'telephone_bills',
        'banking_finance',
        'recreational_activities',
        'insurance',
        'pharmaceuticals',
        'education',
        'diet_reference',
        'waste_handling',
        'lifestyle_preferred_diet_id'
    ];

    public function selectedCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency');
    }

    public function preferredDiet()
    {
        return $this->belongsTo(LifestylePreferredDietFactor::class, 'lifestyle_preferred_diet_id');
    }
}


