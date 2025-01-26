<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\AccountType;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsersCollection;
use App\Services\AdminAnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected AdminAnalyticsService $analytics;
    public function __construct(AdminAnalyticsService $analytics)
    {
        $this->resourceCollection = UsersCollection::class;
        $this->analytics = $analytics;
    }

    public function index()
    {
        return $this->respondWithCustomData(
            data: $this->analytics->dashboardReport()
        );
    }

    public function targetUsers(Request $request, AccountType $target)
    {
        return $this->respondWithCollection($this->analytics->targetUsersData($target, $request->toArray()));
    }

    public function targetGroups(Request $request, $target)
    {
        return $this->respondWithCustomData(
            data: $this->analytics->usersGroupedStats($target)
        );
    }
}
