<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubService
{
    protected string $organization = 'bbwheroes';
    protected ?string $token;

    public function __construct()
    {
        $this->token = config('services.github.token');
    }

    /**
     * Fetch repositories from GitHub organization
     * Cached for 1 hour
     */
    public function getRepositories(): array
    {
        return Cache::remember('github_repositories', 3600, function () {
            try {
                $headers = [];
                if ($this->token) {
                    $headers['Authorization'] = "Bearer {$this->token}";
                }

                $response = Http::withHeaders($headers)
                    ->get("https://api.github.com/orgs/{$this->organization}/repos", [
                        'per_page' => 100,
                        'type' => 'public',
                    ]);

                if (!$response->successful()) {
                    Log::error('GitHub API request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return [];
                }

                $repos = $response->json();

                // Filter repositories that match the 4-part format: module-teacher-taskname-username
                return collect($repos)
                    ->filter(function ($repo) {
                        $parts = explode('-', $repo['name']);
                        return count($parts) >= 4;
                    })
                    ->map(function ($repo) {
                        $parts = explode('-', $repo['name']);
                        
                        // Extract the 4 parts
                        $module = $parts[0];
                        $teacher = $parts[1];
                        $username = array_pop($parts); // Last part is username
                        $taskName = implode('-', array_slice($parts, 2)); // Everything in between

                        return [
                            'id' => $repo['id'],
                            'name' => $repo['name'],
                            'module' => $module,
                            'teacher' => $teacher,
                            'task_name' => $this->unslugify($taskName),
                            'slugified_task_name' => $taskName,
                            'username' => $username,
                            'html_url' => $repo['html_url'],
                            'description' => $repo['description'],
                            'created_at' => $repo['created_at'],
                            'updated_at' => $repo['updated_at'],
                        ];
                    })
                    ->values()
                    ->toArray();
            } catch (\Exception $e) {
                Log::error('Failed to fetch GitHub repositories', [
                    'error' => $e->getMessage(),
                ]);
                return [];
            }
        });
    }

    /**
     * Convert slugified name back to readable format
     */
    protected function unslugify(string $slug): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $slug));
    }

    /**
     * Clear the repository cache
     */
    public function clearCache(): void
    {
        Cache::forget('github_repositories');
    }
}
