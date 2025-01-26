<?php

namespace App\Services;

use App\Enums\AccountRolesEnum;
use App\Models\CarbonEmissionGoal;
use App\Models\User;
use App\Support\Traits\GenericServicesTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoalSettingService
{

    use GenericServicesTrait;

    public function save($requestData): bool
    {
        $minEnEmission = $requestData['min_energy_emission'] ?? 0;
        $maxEnEmission = $requestData['max_energy_emission'] ?? 0;

        $minTrEmission = $requestData['min_transportation_emission'] ?? 0;
        $maxTrEmission = $requestData['max_transportation_emission'] ?? 0;

        $minLiEmission = $requestData['min_lifestyle_emission'] ?? 0;
        $maxLiEmission = $requestData['max_lifestyle_emission'] ?? 0;

        $user = self::user();
        $business = $user->business;

        CarbonEmissionGoal::create([
            'business_id' => $business? $business->id : null,
            'min_target_energy_emission' => $minEnEmission,
            'max_target_energy_emission' => $maxEnEmission,
            'min_target_transportation_emission' => $minTrEmission,
            'max_target_transportation_emission' => $maxTrEmission,
            'min_target_lifestyle_emission' => $minLiEmission,
            'max_target_lifestyle_emission' => $maxLiEmission,
            'user_id' => $user->id
        ]);

        return true;
    }

    public function getGoalSettingAnalysis($request): array
    {
        $reportQuery = $request['report_query'] ?? null;
        $user = self::user();
        $userCurrentEmissionGoal = $user->currentEmissionGoal();
        $response = [];

        if ($reportQuery) {
            $queries = array_map('trim', explode(',', $reportQuery));
            $includeAll = $reportQuery === 'all';

            // Calculate total current emission goal count
            if ($includeAll || in_array('totalCurrentEmissionGoalCount', $queries)) {
                $totalCurrentEmissionGoalCount = 0;

                if ($userCurrentEmissionGoal) {
                    $totalCurrentEmissionGoalCount = $userCurrentEmissionGoal->min_target_energy_emission
                        + $userCurrentEmissionGoal->min_target_transportation_emission
                        + $userCurrentEmissionGoal->min_target_lifestyle_emission;
                }

                $response['totalCurrentEmissionGoalCount'] = number_format((float)$totalCurrentEmissionGoalCount, 2);
            }

            // Fetch latest carbon emission
            if ($includeAll || in_array('latestCarbonEmission', $queries)) {
                $response['latestCarbonEmission'] = $user->latestCarbonEmission();
            }

            // Get current emission goal
            if ($includeAll || in_array('currentEmissionGoal', $queries)) {
                $response['currentEmissionGoal'] = $userCurrentEmissionGoal;
            }

            // Fetch businesses' carbon emission report
            if ($includeAll || in_array('businessesCarbonEmissionReport', $queries)) {
                $response['businessesCarbonEmissionReport'] = self::fetchBusinessesCarbonEmissionReport();
            }
        }

        return $response;
    }



    private static function fetchBusinessesCarbonEmissionReport(): mixed
    {
        return  User::where('role_id', AccountRolesEnum::BUSINESS_OWNER->value)
            ->leftJoin('businesses', 'businesses.id', '=', 'users.business_id')
            ->leftJoin('carbon_footprints as latest_cf', function ($join) {
                $join->on('latest_cf.user_id', '=', 'users.id')
                    ->whereIn('latest_cf.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('carbon_footprints')
                            ->groupBy('user_id');
                    });
            })
            ->select(
                'businesses.name as business_name',
                'latest_cf.*'
            )
            ->get();
    }

}
