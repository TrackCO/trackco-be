<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarbonEmissionDataRequest;
use App\Http\Requests\EnergyEmissionCalculationEmissionRequest;
use App\Http\Requests\LifestyleEmissionCalculationEmissionRequest;
use App\Http\Requests\TransportationEmissionCalculationEmissionRequest;
use App\Http\Requests\DemoCalculatorRequest;
use App\Http\Resources\CarbonFootprintCollection;
use App\Services\CarbonEmissionCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class CarbonEmissionCalculatorController extends Controller
{
    protected CarbonEmissionCalculatorService $carbonEmissionCalculatorService;

    public function __construct(CarbonEmissionCalculatorService $carbonEmissionCalculatorService) {
        $this->carbonEmissionCalculatorService = $carbonEmissionCalculatorService;
        $this->resourceCollection = CarbonFootprintCollection::class;
    }

    /**
     * @param EnergyEmissionCalculationEmissionRequest $request
     * @return JsonResponse
     */
    public function calculateEnergyEmission(EnergyEmissionCalculationEmissionRequest $request): JsonResponse
    {
         return $this->respondWithCustomData($this->carbonEmissionCalculatorService->calculateEnergyEmission($request->validated()));
    }

    /**
     * @param TransportationEmissionCalculationEmissionRequest $request
     * @return JsonResponse
     */
    public function calculateTransportationEmission(TransportationEmissionCalculationEmissionRequest $request): JsonResponse
    {
        return $this->respondWithCustomData($this->carbonEmissionCalculatorService->calculateTransportationEmission($request->validated()));
    }

    /**
     * @param LifestyleEmissionCalculationEmissionRequest $request
     * @return JsonResponse
     */
    public function calculateLifestyleEmission(LifestyleEmissionCalculationEmissionRequest $request): JsonResponse
    {
        return $this->respondWithCustomData($this->carbonEmissionCalculatorService->calculateLifestyleEmission($request->validated()));
    }

    /**
     * @param CarbonEmissionDataRequest $request
     * @return JsonResponse
     */
    public function saveCarbonEmissionData(CarbonEmissionDataRequest $request): JsonResponse
    {
        return $this->respondWithCustomData($this->carbonEmissionCalculatorService->saveCarbonEmissionData($request->validated()));
    }

    public function histories(Request $request): CarbonFootprintCollection
    {
        return $this->respondWithCollection($this->carbonEmissionCalculatorService->getEmissionHistories($request->toArray()));
    }

    public function downloadHistories(Request $request)
    {
        return $this->carbonEmissionCalculatorService->downloadEmissionHistories($request->toArray());
    }

    /**
     * Process a demo calculation
     * 
     * @request location
     * 
     */
    public function demo(DemoCalculatorRequest $request)
    {
        return $this->respondWithCustomData($this->carbonEmissionCalculatorService->processDemo($request->validated()));
    }
}
