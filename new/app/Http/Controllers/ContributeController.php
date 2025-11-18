<?php

namespace App\Http\Controllers;

use App\Models\ContributionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        $this->sendDiscordNotification($contributionRequest);

        return redirect()->route('home')->with('success', 'Your contribution request has been submitted!');
    }

    protected function sendDiscordNotification(ContributionRequest $request): void
    {
        $webhookUrl = config('services.discord.webhook_url');
        
        if (!$webhookUrl) {
            return;
        }

        $adminUrl = route('filament.admin.resources.contribution-requests.view', ['record' => $request->id]);

        $collaboratorsText = empty($request->collaborators) 
            ? '-' 
            : implode(', ', $request->collaborators);

        $embed = [
            'title' => '📝 New Contribution Request',
            'color' => 11844863, // BBW green color
            'fields' => [
                [
                    'name' => 'Module',
                    'value' => $request->module,
                    'inline' => true,
                ],
                [
                    'name' => 'Teacher',
                    'value' => $request->teacher,
                    'inline' => true,
                ],
                [
                    'name' => 'Task Name',
                    'value' => $request->task_name,
                    'inline' => false,
                ],
                [
                    'name' => 'GitHub Username',
                    'value' => "[{$request->github_username}](https://github.com/{$request->github_username})",
                    'inline' => false,
                ],
                [
                    'name' => 'Collaborators',
                    'value' => $collaboratorsText,
                    'inline' => false,
                ],
                [
                    'name' => 'Admin Panel',
                    'value' => "[Review Request]({$adminUrl})",
                    'inline' => false,
                ],
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        try {
            Http::post($webhookUrl, [
                'username' => 'BBW Heroes',
                'embeds' => [$embed],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send Discord notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
