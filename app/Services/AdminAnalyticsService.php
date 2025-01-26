<?php

namespace App\Services;

use App\Enums\AccountRolesEnum;
use App\Enums\AccountType;
use App\Exceptions\ClientErrorException;
use App\Http\Resources\BusinessResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersCollection;
use App\Models\Business;
use App\Models\CarbonFootprint;
use App\Models\User;
use App\Support\Traits\GenericServicesTrait;

class AdminAnalyticsService
{
    use GenericServicesTrait;

    private static array $targetGroups = [
        'byCountry' => 'groupUsersByCountry',
        'byIndustry' => 'groupBusinessesByIndustry'
    ];

    public function dashboardReport()
    {
        $userInstance = $this->userInstance();
        $clientInstance = $userInstance->where('account_type_id', '!=', AccountType::ADMIN->value);
        $carbonInstance = new CarbonFootprint;


        $totalUsers = (clone $clientInstance)->count();
        $totalIndividualUsers = (clone $clientInstance)->where('users.account_type_id', AccountType::INDIVIDUAL->value)->count();
        $totalBusinessUsers = (clone $clientInstance)->where('users.account_type_id', AccountType::BUSINESS->value)->count();
        $activeUsers = (clone $clientInstance)->where('users.status', true)->count();
        $top10Countries = (clone $clientInstance)->selectRaw('countries.name as countryName, count(*) as total')->groupBy('users.country_id')->orderBy('total', 'desc')->limit(10)->get();
        $top5CountriesWithHighestEmissions = (clone $carbonInstance)->leftJoin('countries', 'countries.id', '=', 'carbon_footprints.country_id')
                                                                ->selectRaw('countries.name as countryName, sum(total_emission) as total')
                                                                ->groupBy('carbon_footprints.country_id')
                                                                ->orderBy('total', 'desc')->limit(5)->get();

        $recentBusinesses = User::where('users.account_type_id', AccountType::BUSINESS->value)->orderBy('users.created_at', 'desc')->limit(5)->get();
        $recentIndividuals = User::where('users.account_type_id', AccountType::INDIVIDUAL->value)->orderBy('users.created_at', 'desc')->limit(5)->get();

        $top10Industries = Business::selectRaw('industry, count(*) as total')
            ->groupBy('industry')
            ->orderBy('total', 'desc')->limit(10)->get();

        $top5IndustriesWithHighestEmissions = Business::leftJoin('carbon_footprints', 'carbon_footprints.user_id', '=', 'businesses.created_by')->selectRaw('industry, sum(carbon_footprints.total_emission) as total')
            ->groupBy('businesses.industry')
            ->orderBy('total', 'desc')->limit(5)->get();

        $top5Footprints =  (clone $carbonInstance)->selectRaw('name, total_emission as total')->orderBy('total_emission', 'desc')->limit(5)->get();
        $totalProjects = (clone $carbonInstance)->count();
        return [
            'totalUsers' => $totalUsers,
            'totalIndividuals' => $totalIndividualUsers,
            'totalBusinessUsers' => $totalBusinessUsers,
            'activeUsers' => $activeUsers,
            'top10Countries' => $top10Countries,
            'top10Industries' => $top10Industries,
            'top5IndustriesWithHighestEmissions' => $top5IndustriesWithHighestEmissions,
            'top5CountriesWithHighestEmissions' => $top5CountriesWithHighestEmissions,
            'recentBusinesses' => new UsersCollection($recentBusinesses),
            'recentIndividuals' => new UsersCollection($recentIndividuals),
            'top5Footprints' => $top5Footprints,
            'totalProjects' => $totalProjects,
        ];

    }

    public function targetUsersData(AccountType $target, array $filters)
    {
        if(!$target) throw new ClientErrorException('Invalid account type filter');
        $userInstance = $this->userInstance();

        $limit = $filters['limit'] ?? 20;
        $order = $filters['order'] ?? 'desc';

        return $this->userInstance()->bySearch($filters)
                            ->where('users.account_type_id', $target->id)
                            ->orderBy('users.created_at', $order)
                            ->paginate($limit);
    }


    protected function userInstance()
    {
        return User::leftJoin('countries', 'countries.id', '=', 'users.country_id');
    }

    public function usersGroupedStats(string $target)
    {
        if(!in_array($target, array_keys(self::$targetGroups), true)) throw new ClientErrorException('Invalid target group.');
        $target = self::$targetGroups[$target];
        return $this->$target();
    }

    private function groupUsersByCountry()
    {
        $userInstance = $this->userInstance();
        $clientInstance = $userInstance->where('users.account_type_id', '!=', AccountType::ADMIN->value);
        return $clientInstance->selectRaw('countries.name as countryName, count(*) as total')
                            ->groupBy('users.country_id')
                            ->orderBy('total', 'desc')->get();
    }

    private function groupBusinessesByIndustry()
    {
        return Business::selectRaw('industry, count(*) as total')
                        ->groupBy('industry')
                        ->orderBy('total', 'desc')->get();
    }

    public function businessAnalyticsReport(): array
    {
        $userInstance = $this->userInstance();
        $clientInstance = (clone $userInstance)->where('users.account_type_id', AccountType::BUSINESS->value);
        $businessesCount = (clone $clientInstance)->count();
        $employeesCount = (clone $clientInstance)->where('users.role_id', AccountRolesEnum::EMPLOYEE->value)->count();
        $carbonFootprintStats = Business::leftJoin('carbon_footprints', 'carbon_footprints.user_id', '=', 'businesses.created_by');
        $totalEmission = (clone $carbonFootprintStats)->sum('carbon_footprints.total_emission');
        $totalEnergyEmission = (clone $carbonFootprintStats)->sum('carbon_footprints.energy_emission');
        $totalTransportationEmission = (clone $carbonFootprintStats)->sum('carbon_footprints.transportation_emission');
        $totalLifestyleEmission = (clone $carbonFootprintStats)->sum('carbon_footprints.lifestyle_emission');
        $top5Countries = (clone $clientInstance)->selectRaw('countries.name as countryName, count(*) as total')->groupBy('users.country_id')->orderBy('total', 'desc')->limit(5)->get();
        $top5Industries = Business::selectRaw('industry, count(*) as total')->groupBy('industry')->orderBy('total', 'desc')->limit(5)->get();

        return [
            'businessCount' => $businessesCount,
            'employeesCount' => $employeesCount,
            'totalEmission' => $totalEmission,
            'totalEnergyEmission' => $totalEnergyEmission,
            'totalTransportationEmission' => $totalTransportationEmission,
            'totalLifestyleEmission' => $totalLifestyleEmission,
            'top5Countries' => $top5Countries,
            'top5Industries' => $top5Industries,
        ];

    }

    public function individualAnalyticsReport(): array
    {
        $userInstance = $this->userInstance();
        $clientInstance = (clone $userInstance)->where('users.account_type_id', AccountType::INDIVIDUAL->value);
        $usersCount = (clone $clientInstance)->count();
        $employeesCount = (clone $userInstance)->where('users.role_id', AccountRolesEnum::EMPLOYEE->value)->count();
        $carbonFootprintStats = (clone $clientInstance)->leftJoin('carbon_footprints', 'carbon_footprints.user_id', '=', 'users.id');
        $totalEmission = (clone $carbonFootprintStats)->sum('carbon_footprints.total_emission');
        $totalEnergyEmission = (clone $carbonFootprintStats)->sum('carbon_footprints.energy_emission');
        $totalTransportationEmission = (clone $carbonFootprintStats)->sum('carbon_footprints.transportation_emission');
        $totalLifestyleEmission = (clone $carbonFootprintStats)->sum('carbon_footprints.lifestyle_emission');
        $top5Countries = (clone $clientInstance)->selectRaw('countries.name as countryName, count(*) as total')->groupBy('users.country_id')->orderBy('total', 'desc')->limit(5)->get();
        $top5Industries = Business::selectRaw('industry, count(*) as total')->groupBy('industry')->orderBy('total', 'desc')->limit(5)->get();

        return [
            'businessCount' => $usersCount,
            'employeesCount' => $employeesCount,
            'totalEmission' => $totalEmission,
            'totalEnergyEmission' => $totalEnergyEmission,
            'totalTransportationEmission' => $totalTransportationEmission,
            'totalLifestyleEmission' => $totalLifestyleEmission,
            'top5Countries' => $top5Countries,
            'top5Industries' => $top5Industries,
        ];

    }

    /**
     * @param array $filterParams
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function businessLists(array $filterParams): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $businesses = Business::selectRaw("businesses.*")->leftJoin('users', 'users.id', 'businesses.created_by')
                                ->bySearch($filterParams)
                                ->get();
        return BusinessResource::collection($businesses);
    }

    /**
     * @param array $filterParams
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function individualLists(array $filterParams): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $businesses = $this->userInstance()->where('users.account_type_id', AccountType::INDIVIDUAL->value)
            ->bySearch($filterParams)
            ->get();
        return UserResource::collection($businesses);
    }

}
