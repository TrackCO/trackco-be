<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonEmissionGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'min_target_energy_emission',
        'max_target_energy_emission',
        'min_target_transportation_emission',
        'max_target_transportation_emission',
        'min_target_lifestyle_emission',
        'max_target_lifestyle_emission',
        'achieved_energy_emission',
        'achieved_transportation_emission',
        'achieved_lifestyle_emission',
        'carbon_emission_goal_status_id',
        'business_id'
    ];
}
