<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request)
    {
        return $this->respondWithCustomData(
            $this->projectService->listProjects()
        );
    }

    public function create(CreateProjectRequest $request)
    {
        return $this->respondWithCustomData(
            $this->projectService->saveProject($request)
        );
    }
}
