<?php

namespace App\Services;

use App\Http\Resources\CountryResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\EnergySourceResource;
use App\Models\Country;
use App\Models\Currency;
use App\Models\EnergySource;
use App\Models\HouseType;
use App\Models\LifestylePreferredDietFactor;
use App\Models\LifestyleSectorFactor;
use App\Strategies\UtilitiesStrategy;

class UtilitiesService implements UtilitiesStrategy
{

    public static function countries()
    {
       $countries = Country::all();
       return CountryResource::collection($countries);
    }

    public static function energySources()
    {
        $energySources = EnergySource::all();
        return EnergySourceResource::collection($energySources);
    }

    public static function currencies()
    {
        $currencies = Currency::all();
        return CurrencyResource::collection($currencies);
    }

    public static function lifestyleSectors()
    {
        return LifestyleSectorFactor::all();
    }

    public static function lifestylePreferredDiets()
    {
        return LifestylePreferredDietFactor::all();
    }

    /**
     * @return mixed
     * @throws \App\Exceptions\ClientErrorException
     */
    public static function countriesEmissionDataset(): mixed
    {
        return getDatasetFromJson();
    }

    public static function houseTypes()
    {
        return HouseType::all();
    }
}
