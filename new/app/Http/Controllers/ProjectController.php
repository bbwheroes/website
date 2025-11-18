<?php

namespace App\Http\Controllers;

use App\Services\GitHubService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(GitHubService $github)
    {
        $projects = $github->getRepositories();
        $projectsCount = count($projects);

        return view('projects', compact('projects', 'projectsCount'));
    }
}
