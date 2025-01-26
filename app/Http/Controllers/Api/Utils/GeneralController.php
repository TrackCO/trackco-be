<?php

namespace App\Http\Controllers\Api\Utils;

use App\Exceptions\ClientErrorException;
use App\Http\Controllers\Controller;
use App\Services\UtilitiesService;
use App\Support\Traits\ResponseTrait;

class GeneralController extends Controller
{
    protected UtilitiesService $utilitiesService;
    public function __construct(UtilitiesService $utilitiesService){
        $this->utilitiesService = $utilitiesService;
    }

    public function __invoke(string $target){
        if(!method_exists($this->utilitiesService, $target)) {
            throw new ClientErrorException('Invalid utility entity.');
        }
        return $this->respondWithCustomData($this->utilitiesService::$target());
    }

}
