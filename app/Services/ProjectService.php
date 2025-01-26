<?php

namespace App\Services;

use App\Exceptions\ClientErrorException;
use App\Models\Project;
use App\Support\Traits\GenericServicesTrait;

class ProjectService
{
    use GenericServicesTrait;

    private FileUploaderService $fileUploaderService;

    public function __construct(FileUploaderService $fileUploaderService)
    {
        $this->fileUploaderService = $fileUploaderService;
    }

    public function saveProject($requestData)
    {
        $user = self::user();
        $file = $requestData->image;
        if(!$file) throw new ClientErrorException('No file provided.');
        $uploadedFile =  $this->fileUploaderService->uploadFileToLocal($file, 'uploads/projects');

        $project = Project::create([
            'name' => $requestData['name'],
            'amount' => $requestData['amount'],
            'available_tonnes' => $requestData['available_tonnes'],
            'description' => $requestData['description'],
            'image_url' => asset($uploadedFile),
            'country_id' => $requestData['country_id'],
            'size' => $requestData['size'],
            'type' => $requestData['type'],
            'project_category_id' => $requestData['project_category_id'],
            'developer_name' => $requestData['developer_name'],
            'eligibility' => $requestData['eligibility'],
            'standard' => $requestData['standard'],
            'methodology' => $requestData['methodology'],
            'additional_certificates' => $requestData['additional_certificates'],
            'cbb_validator' => $requestData['cbb_validator'],
            'project_validator' => $requestData['project_validator'],
            'issue_date' => $requestData['issue_date'],
            'user_id' => $user->id
        ]);

        return $project;
    }

    public function listProjects()
    {
        return Project::orderBy('id', 'desc')->get();
    }

}
