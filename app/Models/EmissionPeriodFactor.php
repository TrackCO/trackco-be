<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmissionPeriodFactor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getByName(string $name){
        return self::where('name', $name)->first();
    }
}
