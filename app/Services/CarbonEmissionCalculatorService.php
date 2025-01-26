<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 01/07/2024
 * Time: 4:23 pm
 */

namespace App\Services;

use App\Enums\AccountRolesEnum;
use App\Enums\AccountType;
use App\Exceptions\ClientErrorException;
use App\Models\Business;
use App\Models\CarbonFootprint;
use App\Models\CarTypeFactor;
use App\Models\Country;
use App\Models\Currency;
use App\Models\EmissionLifestyle;
use App\Models\EmissionPeriodFactor;
use App\Models\EmissionTransportation;
use App\Models\EnergyConsumption;
use App\Models\EnergyConsumptionSource;
use App\Models\EnergySource;
use App\Models\EnergyUnitFactor;
use App\Models\HouseType;
use App\Models\LifestylePeriodFactor;
use App\Models\LifestylePreferredDietFactor;
use App\Models\LifestyleSectorFactor;
use App\Models\User;
use App\Notifications\DemoEmissionCalculatorReport;
use App\Support\Traits\CalculatorTrait;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\EmissionHistoriesExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CarbonEmissionCalculatorService
{
    use CalculatorTrait;

    /**
     * Process simple demo calculation...
     *
     * @param array $data
     * @return array
     */
    public function processDemo(array $data): array
    {
        $electConsumption = $data['electricity_consumption'] ??  1;
        $location = Country::find($data['location']);
        $countryName = $location?->name;
        $countryEmissionDataset = $this->searchCountryInDataset($countryName);
        $countryAverage = $countryEmissionDataset ? $countryEmissionDataset['co2_monthly'] : 0.00;
        $responseData = [
            'total' => number_format($countryAverage * $electConsumption / 1000, 2),
            'country' => $countryName,
            'countryEmissionFactor' => $countryAverage
        ];
        if($data['sendReport']) return $this->processDemoReport($data, $responseData);

        return $responseData;
    }

    public function processDemoReport(array $data, $responseData): array
    {
        if(!$user = User::where('email', $data['email'])->first()) {
            $user = User::create([
                'country_id' => $data['location'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'full_name' => $data['first_name'] . ' ' . $data['last_name'],
                'role_id' => $data['account_type'] === 'individual' ? AccountRolesEnum::INDIVIDUAL->value : AccountRolesEnum::BUSINESS_OWNER->value,
                'account_type_id' => $data['account_type'] === 'individual' ? AccountType::INDIVIDUAL->value : AccountType::BUSINESS->value,
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'referral_code' => generateRandomCharacters(15)
            ]);

            $business = Business::create([
                'name' => $data['company_name'],
                'industry' => $data['industry'],
                'created_by' => $user->id,
            ]);

            $user->business_id = $business->id;
            $user->save();
        }


        $user->notify(new DemoEmissionCalculatorReport([...$data, ...$responseData]));

        return [
            'status' => true
        ];
    }


    public function calculateEnergyEmission(array $data): array
    {
        $filterFromMonth = $data['filterFromMonth'];
        $filterToMonth = $data['filterToMonth'];
        $monthDiff = $this->calculateMonthDifference($filterFromMonth, $filterToMonth);
        $noOfEmployees = $data['no_of_employees'] ?? 1;
        $location = Country::find($data['location']);
        $countryName = $location?->name;
        $countryEmissionDataset = $this->searchCountryInDataset($countryName);
        $countryAverage = $countryEmissionDataset ? $countryEmissionDataset['fossil_co2_yearly_emission'] : 0.00;
        $countryEmissionFactor = (($countryEmissionDataset ? (float)str_replace(',', '', $countryEmissionDataset['co2_monthly']) : 0.5) / 12) * $monthDiff;
        $electricityConsumption = $data['electricity_consumption'] ?? 0;
        $houseType = $data['house_type'] ?? 0;
        if($houseType){
            $houseType = HouseType::find($houseType)->factor;
        }
        $size = $data['size'] ?? 0;
        $solar = $data['solar'] ?? 0;
        $wind = $data['wind'] ?? 0;
        $hydro_power = $data['hydro_power'] ?? 0;
        $nuclear = $data['nuclear'] ?? 0;

        // Fetch values and factors for each energy source
        $energySources = [
            'natural_gas' => ['value' => $data['natural_gas'] ?? 0, 'unit' => $data['natural_gas_unit']],
            'heating_oil' => ['value' => $data['heating_oil'] ?? 0, 'unit' => $data['heating_oil_unit']],
            'coal' => ['value' => $data['coal'] ?? 0, 'unit' => $data['coal_unit']],
            'lpg' => ['value' => $data['lpg'] ?? 0, 'unit' => $data['lpg_unit']],
            'wooden_pellets' => ['value' => $data['wooden_pellets'] ?? 0, 'unit' => $data['wooden_pellets_unit']],
            'propane' => ['value' => $data['propane'] ?? 0, 'unit' => $data['propane_unit']],
        ];

        $totalEmission = 0;

        foreach ($energySources as $source => $details) {
            $factor = $this->getEnergyUnitFactor($details['unit'], $source);
            $factor = $factor ? $factor->factor : 0;
            $totalEmission += $details['value'] * $factor;
        }
        $subTotalEmission = $totalEmission;
        $totalEmission *= $countryEmissionFactor;

        // Scale emissions by number of employees
        $scaledEmission = $subTotalEmission ? (((((($totalEmission / $noOfEmployees)/$subTotalEmission)
                    + $countryEmissionFactor + $size + $solar + $wind + $hydro_power + $nuclear + $electricityConsumption + $houseType) * ($subTotalEmission / $noOfEmployees)) / 1000)
                + $countryEmissionFactor) * $countryEmissionFactor : (((($electricityConsumption/$noOfEmployees) * $countryEmissionFactor) / $countryEmissionFactor) / 1000) + $houseType;

        return [
            'total' =>  number_format((float)$scaledEmission, 2),
            'country' => $countryName,
            'countryEmissionFactor' => $countryAverage
        ];
    }

    private function getEnergyUnitFactor(int $unitId, string $energySourceSlug)
    {
        $energySource = $this->getEnergySourceUsingSlug($energySourceSlug);
        return EnergyUnitFactor::where('unit_id', $unitId)
                            ->where('energy_source_id', $energySource->id)->first();
    }

    private function getEnergySourceUsingSlug(string $energySourceSlug){
        return EnergySource::where('slug', $energySourceSlug)->first();
    }



    public function calculateTransportationEmission(array $data): array
    {
        $bikeRate = $data['bike_rate'] ?? 0;
        $bikePeriodFactor =  EmissionPeriodFactor::getByName($data['bike_period'])->factor;

        $cityBusRate = $data['city_bus_rate'] ?? 0;
        $cityBusPeriodFactor = EmissionPeriodFactor::getByName($data['city_bus_period'])->factor;

        $trainRate = $data['train_rate'] ?? 0;
        $trainPeriodFactor = EmissionPeriodFactor::getByName($data['train_period'])->factor;

        $walkRate = $data['walk_rate'] ?? 0;
        $walkPeriodFactor = EmissionPeriodFactor::getByName($data['walk_period'])->factor;

        $mTransport = (($bikeRate * $bikePeriodFactor) / $bikePeriodFactor)
            + (($cityBusRate + $cityBusPeriodFactor) / $cityBusPeriodFactor)
            + (($trainRate * $trainPeriodFactor) / $trainPeriodFactor)
            + (($walkRate * $walkPeriodFactor) / $walkPeriodFactor);

        $carDetailsEmission = isset($data['car_details']) ? $this->calculateCarDetailEmission($data) : 1;
        $vLongFlightMax = isset($data['flight_very_long_max']) ? $data['flight_very_long_max'] : 0;
        $vLongFlightMin = isset($data['flight_very_long_min']) ? $data['flight_very_long_min'] : 0;
        $longFlightMax = isset($data['flight_long_max']) ? $data['flight_long_max'] : 0;
        $longFlightMin = isset($data['flight_long_min']) ? $data['flight_long_min'] : 0;
        $mediumFlightMax = isset($data['flight_medium_max']) ? $data['flight_medium_max'] : 0;
        $mediumFlightMin = isset($data['flight_medium_min']) ? $data['flight_medium_min'] : 0;
        $shortFlightMax = isset($data['flight_short_max']) ? $data['flight_short_max'] : 0;
        $shortFlightMin = isset($data['flight_short_min']) ? $data['flight_short_min'] : 0;
        $total = ($vLongFlightMax + $vLongFlightMin
                    + $longFlightMax + $longFlightMin
                    + $mediumFlightMax + $mediumFlightMin
                    + $shortFlightMax + $shortFlightMin) / $carDetailsEmission;

        return [
            'total' => number_format((($mTransport + $total) / $carDetailsEmission) / 1000, 2)
        ];
    }

    private function calculateCarDetailEmission($data): float
    {
        $total = 0;

        foreach ($data['car_details'] as $carDetail) {
            $carTypeFactor = CarTypeFactor::getByName($carDetail['type'])->factor;
            $carAnnualMileage = $carDetail['annual_mileage'] ?? 0;
            $averageConsumption = $carDetail['average_consumption'] ?? 0;
            $total += ($carTypeFactor + $carAnnualMileage / $averageConsumption);
        }
        return $total;
    }

    private function getLifestyleSectorFactor(string $sector)
    {
        $__sector = LifestyleSectorFactor::where('slug', $sector)->first();
        return $__sector ? $__sector->factor : 0;
    }

    public function calculateLifestyleEmission(array $data): array
    {
        $currency = isset($data['currency']) ? Currency::find($data['currency']) : null;
        $currencyRate = $currency ? $currency->rate : 1;

        $preferredDietFactor = isset($data['preferred_diet']) ? LifestylePreferredDietFactor::getByName($data['preferred_diet'])->factor : 1;

        $lifestyleSectors = [
            'paper_based_products',
            'banking_and_finance',
            'motor_vehicles',
            'hotels_restaurants',
            'insurance',
            'education',
            'pharmaceuticals',
            'cloths_and_shoes',
            'recreational_activities',
            'furniture'
        ];

        $totalLifestyleSectorEmission = 0;

        foreach ($lifestyleSectors as $lifestyleSector) {
            $exponent = isset($data[$lifestyleSector]) ? $data[$lifestyleSector] : 1;
            $unitFactor = isset($data[$lifestyleSector]) ? $this->getLifestyleSectorFactor($lifestyleSector) : 1;
            $totalLifestyleSectorEmission += ((($exponent * $unitFactor) / 1000) * $unitFactor) / $currencyRate;
        }

        $total = ($totalLifestyleSectorEmission  * $preferredDietFactor) / $currencyRate;

        return [
            'total' => number_format($total, 2)
        ];
    }

    public function saveCarbonEmissionData(array $data)
    {
        $user = auth()->user();
        $filterFromMonth = $data['filterFromMonth'];
        $filterToMonth = $data['filterToMonth'];
        $carbonFootprint = CarbonFootprint::create([
            'user_id' => $user->id,
            'name' => $data['emissionTitle'],
            'country_id' => $data['location'],
            'energy_emission' => $data['calculatedValues']['energy'],
            'lifestyle_emission' => $data['calculatedValues']['lifestyle'],
            'transportation_emission' => $data['calculatedValues']['transportation'],
            'total_emission' => $data['calculatedValues']['total'],
            'number_of_employees' => $data['no_of_employees'] ?? 1,
            'month_from' => $filterFromMonth,
            'month_to' => $filterToMonth
        ]);

        $houseType = $data['house_type'] ?? null;
        if($houseType){
            $houseType = HouseType::find($houseType)->id;
        }
        $size = $data['size'] ?? 0;
        $solar = $data['solar'] ?? 0;
        $wind = $data['wind'] ?? 0;
        $hydro_power = $data['hydro_power'] ?? 0;
        $nuclear = $data['nuclear'] ?? 0;

        $bikeRate = $data['bike_rate'] ?? 0;
        $cityBusRate = $data['city_bus_rate'] ?? 0;
        $trainRate = $data['train_rate'] ?? 0;
        $walkRate = $data['walk_rate'] ?? 0;

        $vLongFlightMax = isset($data['flight_very_long_max']) ? $data['flight_very_long_max'] : 0;
        $vLongFlightMin = isset($data['flight_very_long_min']) ? $data['flight_very_long_min'] : 0;
        $longFlightMax = isset($data['flight_long_max']) ? $data['flight_long_max'] : 0;
        $longFlightMin = isset($data['flight_long_min']) ? $data['flight_long_min'] : 0;
        $mediumFlightMax = isset($data['flight_medium_max']) ? $data['flight_medium_max'] : 0;
        $mediumFlightMin = isset($data['flight_medium_min']) ? $data['flight_medium_min'] : 0;
        $shortFlightMax = isset($data['flight_short_max']) ? $data['flight_short_max'] : 0;
        $shortFlightMin = isset($data['flight_short_min']) ? $data['flight_short_min'] : 0;


        EmissionTransportation::create([
            'carbon_footprint_id' => $carbonFootprint->id,
            'flight_very_long_max' => $vLongFlightMax,
            'flight_very_long_min' => $vLongFlightMin,
            'flight_long_max' => $longFlightMax,
            'flight_long_min' => $longFlightMin,
            'flight_medium_max' => $mediumFlightMax,
            'flight_medium_min' => $mediumFlightMin,
            'flight_short_max' => $shortFlightMax,
            'flight_short_min' => $shortFlightMin,
            'enabled_mode' => json_encode([
                'bike' => [
                    'period' => $data['bike_period'],
                    'value' => $bikeRate,
                ],
                'city_bus' => [
                    'period' => $data['city_bus_period'],
                    'value' => $cityBusRate,
                ],
                'train' => [
                    'period' => $data['train_period'],
                    'value' => $trainRate,
                ],
                'walk' => [
                    'period' => $data['walk_period'],
                    'value' => $walkRate,
                ],
                'cars' => $data['car_details'] ?? [],
            ], true)
        ]);

        $energyConsumption = EnergyConsumption::create([
            'carbon_footprint_id' => $carbonFootprint->id,
            'electricity_usage' => $data['electricity_consumption'],
            'number_of_employees' => $data['no_of_employees'] ?? 1,
            'house_type_id' => $houseType,
            'size' => $size,
            'solar' => $solar,
            'wind' => $wind,
            'hydro_power' => $hydro_power,
            'nuclear' => $nuclear
        ]);

        $energySources = [
            'natural_gas' => ['value' => $data['natural_gas'] ?? 0, 'unit' => $data['natural_gas_unit']],
            'heating_oil' => ['value' => $data['heating_oil'] ?? 0, 'unit' => $data['heating_oil_unit']],
            'coal' => ['value' => $data['coal'] ?? 0, 'unit' => $data['coal_unit']],
            'lpg' => ['value' => $data['lpg'] ?? 0, 'unit' => $data['lpg_unit']],
            'wooden_pellets' => ['value' => $data['wooden_pellets'] ?? 0, 'unit' => $data['wooden_pellets_unit']],
            'propane' => ['value' => $data['propane'] ?? 0, 'unit' => $data['propane_unit']],
        ];

        foreach ($energySources as $source => $details) {
            EnergyConsumptionSource::create([
                'unit_id' => $details['unit'],
                'energy_source_id' => $this->getEnergySourceUsingSlug($source)->id,
                'value' => $details['value'],
                'energy_consumption_id' => $energyConsumption->id
            ]);
        }

        $lifestylePreferredFactorVal = isset($data['preferred_diet']) ? LifestylePreferredDietFactor::getByName($data['preferred_diet']) : null;
        EmissionLifestyle::create([
            'carbon_footprint_id' => $carbonFootprint->id,
            'currency' =>  $data['currency'] ?? null,
            'paper_products_spending' => $data['paper_based_products'] ?? 0,
            'banking_finance' => $data['banking_and_finance'] ?? 0,
            'recreational_activities' => $data['recreational_activities'] ?? 0,
            'insurance' => $data['insurance'] ?? 0,
            'education' => $data['education'] ?? 0,
            'pharmaceuticals' => $data['pharmaceuticals'] ?? 0,
            'diet_reference' =>  $lifestylePreferredFactorVal ? $lifestylePreferredFactorVal->factor : 0,
            'lifestyle_preferred_diet_id' => $lifestylePreferredFactorVal ? $lifestylePreferredFactorVal->id :null,
        ]);

        return $carbonFootprint;
    }

    public function getEmissionHistories($requestData)
    {
        $user = auth()->user();
        return $user->footprintHistories()
                        ->bySearch($requestData)->get();
    }
    public function downloadEmissionHistories($requestData)
    {
        $histories = $this->getEmissionHistories($requestData);

        $user = auth()->user();
        $username = $user->full_name ?: trim($user->first_name . ' ' . $user->last_name);
        $currentDate = now()->format('Y-m-d');

        $sanitizedUsername = preg_replace('/[^a-zA-Z0-9]/', '_', $username);

        $fileName = "emission_histories_{$sanitizedUsername}_{$currentDate}.csv";

        return Excel::download(new EmissionHistoriesExport($histories), $fileName);
    }
}
