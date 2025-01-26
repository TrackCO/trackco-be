<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersCollection;
use App\Services\AdminAnalyticsService;
use Illuminate\Http\Request;

class BusinessesAnalyticsController extends Controller
{
    protected AdminAnalyticsService $analytics;
    public function __construct(AdminAnalyticsService $analytics)
    {
        $this->resourceCollection = UsersCollection::class;
        $this->analytics = $analytics;
    }

    public function index(Request $request)
    {
        return $this->respondWithCustomData(
            $this->analytics->businessLists($request->toArray()),
        );
    }

    public function reports(Request $request)
    {
        return $this->respondWithCustomData(
            $this->analytics->businessAnalyticsReport(),
        );
    }
}
