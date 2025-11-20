<?php

namespace App\Http\Controllers;

use App\Helpers\DiscordHelper;
use App\Models\ContributionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContributeController extends Controller
{
    public function create()
    {
        return view('contribute');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'module' => 'required|string|size:3',
            'teacher' => 'required|string|size:4',
            'task_name' => 'required|string|max:255',
            'github_username' => 'required|string|max:39',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'string|max:39',
        ]);

        // Create slugified task name
        $slugifiedTaskName = Str::slug($validated['task_name'], '_');

        $contributionRequest = ContributionRequest::create([
            'module' => $validated['module'],
            'teacher' => strtolower($validated['teacher']),
            'task_name' => $validated['task_name'],
            'slugified_task_name' => $slugifiedTaskName,
            'github_username' => strtolower($validated['github_username']),
            'collaborators' => $validated['collaborators'] ?? [],
            'status' => 'pending',
        ]);

        // Send Discord notification
        DiscordHelper::sendNewRequestNotification($contributionRequest);

        return redirect()->route('home')->with('success', 'Your contribution request has been submitted!');
    }
}
