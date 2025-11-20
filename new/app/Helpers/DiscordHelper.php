<?php

namespace App\Helpers;

use App\Models\ContributionRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordHelper
{
    /**
     * Send a notification when a new contribution request is submitted
     */
    public static function sendNewRequestNotification(ContributionRequest $request): void
    {
        $webhookUrl = config('services.discord.webhook_url');
        
        if (!$webhookUrl) {
            return;
        }

        $adminUrl = route('filament.admin.resources.contribution-requests.view', ['record' => $request->id]);
        $githubProfileUrl = "https://github.com/{$request->github_username}";
        $githubAvatarUrl = "https://github.com/{$request->github_username}.png?size=128";

        $collaboratorsText = empty($request->collaborators) 
            ? '-' 
            : implode(', ', array_map(fn($collab) => "[@{$collab}](https://github.com/{$collab})", $request->collaborators));

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
                    'value' => "[@{$request->github_username}]({$githubProfileUrl})",
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
            'thumbnail' => [
                'url' => $githubAvatarUrl,
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        self::sendWebhook($embed, 'BBW Heroes');
    }

    /**
     * Send a notification when a request is accepted or declined
     */
    public static function sendRequestStatusNotification(ContributionRequest $request, string $action): void
    {
        $webhookUrl = config('services.discord.webhook_url');

        if (!$webhookUrl) {
            return;
        }

        $color = $action === 'accepted' ? 3066993 : 15158332; // Green or Red
        $adminUrl = route('filament.admin.resources.contribution-requests.view', ['record' => $request->id]);
        $githubProfileUrl = "https://github.com/{$request->github_username}";
        $githubAvatarUrl = "https://github.com/{$request->github_username}.png?size=128";

        $embed = [
            'title' => $action === 'accepted' ? '✅ Request Accepted' : '❌ Request Declined',
            'color' => $color,
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
                    'name' => 'Task',
                    'value' => $request->task_name,
                    'inline' => false,
                ],
                [
                    'name' => 'GitHub User',
                    'value' => "[@{$request->github_username}]({$githubProfileUrl})",
                    'inline' => false,
                ],
                [
                    'name' => 'Admin Panel',
                    'value' => "[View Details]({$adminUrl})",
                    'inline' => false,
                ],
            ],
            'thumbnail' => [
                'url' => $githubAvatarUrl,
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        self::sendWebhook($embed, 'BBW Heroes Admin');
    }

    /**
     * Send a webhook to Discord
     */
    protected static function sendWebhook(array $embed, string $username = 'BBW Heroes'): void
    {
        $webhookUrl = config('services.discord.webhook_url');
        
        if (!$webhookUrl) {
            return;
        }

        try {
            Http::post($webhookUrl, [
                'username' => $username,
                'embeds' => [$embed],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send Discord notification', [
                'error' => $e->getMessage(),
                'embed' => $embed,
            ]);
        }
    }
}
