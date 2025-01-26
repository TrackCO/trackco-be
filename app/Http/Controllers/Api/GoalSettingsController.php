<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGoalSettingRequest;
use App\Services\GoalSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoalSettingsController extends Controller
{
    /**
     * @var GoalSettingService
     */
    protected GoalSettingService $goalSettingService;

    public function __construct(GoalSettingService $goalSettingService)
    {
        $this->goalSettingService = $goalSettingService;
    }

    /** Goal setting dashboard records/analysis
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->respondWithCustomData(
            $this->goalSettingService->getGoalSettingAnalysis($request->toArray())
        );
    }

    /** Create Goal Setting
     *
     * @param CreateGoalSettingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateGoalSettingRequest $request): JsonResponse
    {
        return $this->respondWithCustomData(
          $this->goalSettingService->save($request->toArray())
        );
    }
}
