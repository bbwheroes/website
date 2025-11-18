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

                $allRepos = [];
                $page = 1;
                $perPage = 100;

                // Paginate through all pages
                do {
                    $response = Http::withHeaders($headers)
                        ->get("https://api.github.com/orgs/{$this->organization}/repos", [
                            'per_page' => $perPage,
                            'page' => $page,
                            'type' => 'public',
                        ]);

                    if (!$response->successful()) {
                        Log::error('GitHub API request failed', [
                            'status' => $response->status(),
                            'body' => $response->body(),
                            'page' => $page,
                        ]);
                        break;
                    }

                    $repos = $response->json();
                    
                    if (empty($repos)) {
                        break;
                    }

                    $allRepos = array_merge($allRepos, $repos);
                    $page++;

                    // GitHub returns less than per_page items on the last page
                } while (count($repos) === $perPage);

                // Filter repositories that match the 4-part format: module-teacher-taskname-username
                return collect($allRepos)
                    ->filter(function ($repo) {
                        $parts = explode('-', $repo['name']);
                        return count($parts) >= 4;
                    })
                    ->map(function ($repo) {
                        $parts = explode('-', $repo['name']);
                        
                        // Extract the 4 parts: module-teacher-taskname-username
                        // Username can contain hyphens, but taskname doesn't
                        $module = $parts[0];
                        $teacher = $parts[1];
                        $taskName = $parts[2]; // Third part is the task name
                        $username = implode('-', array_slice($parts, 3)); // Everything after is username

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
