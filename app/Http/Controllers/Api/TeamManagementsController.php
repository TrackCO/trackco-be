<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamsRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersCollection;
use App\Services\BusinessTeamsService;
use Illuminate\Http\Request;

class TeamManagementsController extends Controller
{
    protected BusinessTeamsService $teamsService;
    protected UserResource $userResource;
    public function __construct(BusinessTeamsService $teamsService)
    {
        $this->teamsService = $teamsService;
        $this->resourceCollection = UsersCollection::class;
        $this->resourceItem = UserResource::class;
    }

    public function create(CreateTeamsRequest $request)
    {
        return $this->respondWithItem(
            $this->teamsService->createTeam(
                $request->toArray()
            )
        );
    }

    public function index()
    {
        return $this->respondWithCollection(
            $this->teamsService->listTeamMembers()
        );
    }

    public function update(Request $request, $team)
    {
        return $this->respondWithItem(
            $this->teamsService->update($team,
                $request->toArray()
            )
        );
    }

    public function show($team)
    {
        return $this->respondWithItem(
            $this->teamsService->detail($team)
        );
    }

    public function destroy(int $id){
        return $this->respondWithCustomData(
            $this->teamsService->removeTeam(
                $id
            )
        );
    }


}
