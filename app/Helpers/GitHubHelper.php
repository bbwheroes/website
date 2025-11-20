<?php

namespace App\Helpers;

use App\Models\ContributionRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubHelper
{
    protected static string $organization = 'bbwheroes';
    protected static ?string $token = null;

    /**
     * Get the GitHub API token
     */
    protected static function getToken(): ?string
    {
        if (self::$token === null) {
            self::$token = config('services.github.token');
        }
        return self::$token;
    }

    /**
     * Get the base headers for GitHub API requests
     */
    protected static function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . self::getToken(),
            'Accept' => 'application/vnd.github+json',
            'X-GitHub-Api-Version' => '2022-11-28',
        ];
    }

    /**
     * Generate repository name from contribution request
     */
    public static function generateRepoName(ContributionRequest $request): string
    {
        return implode('-', [
            $request->module,
            $request->teacher,
            $request->slugified_task_name,
            $request->github_username,
        ]);
    }

    /**
     * Create a repository in the organization
     */
    public static function createRepository(string $repoName): array
    {
        try {
            $response = Http::withHeaders(self::getHeaders())
                ->post("https://api.github.com/orgs/" . self::$organization . "/repos", [
                    'name' => $repoName,
                    'private' => false,
                    'has_issues' => true,
                    'has_projects' => false,
                    'has_wiki' => false,
                    'auto_init' => false,
                ]);

            if (!$response->successful()) {
                Log::error('Failed to create GitHub repository', [
                    'repo_name' => $repoName,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                throw new \Exception('Failed to create repository: ' . $response->body());
            }

            Log::info('GitHub repository created successfully', [
                'repo_name' => $repoName,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception while creating GitHub repository', [
                'repo_name' => $repoName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Invite collaborators to a repository
     */
    public static function inviteCollaborators(string $repoName, array $collaborators, string $permission = 'maintain'): void
    {
        foreach ($collaborators as $collaborator) {
            try {
                $response = Http::withHeaders(self::getHeaders())
                    ->put("https://api.github.com/repos/" . self::$organization . "/{$repoName}/collaborators/{$collaborator}", [
                        'permission' => $permission,
                    ]);

                if (!$response->successful()) {
                    Log::error('Failed to invite collaborator to GitHub repository', [
                        'repo_name' => $repoName,
                        'collaborator' => $collaborator,
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]);
                    continue;
                }

                Log::info('Collaborator invited successfully', [
                    'repo_name' => $repoName,
                    'collaborator' => $collaborator,
                    'permission' => $permission,
                ]);
            } catch (\Exception $e) {
                Log::error('Exception while inviting collaborator', [
                    'repo_name' => $repoName,
                    'collaborator' => $collaborator,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Create repository and invite all collaborators for a contribution request
     */
    public static function setupRepositoryForRequest(ContributionRequest $request): array
    {
        $repoName = self::generateRepoName($request);

        $repoData = self::createRepository($repoName);

        $allCollaborators = array_merge(
            [$request->github_username],
            $request->collaborators ?? []
        );

        self::inviteCollaborators($repoName, $allCollaborators);

        return $repoData;
    }

    /**
     * Check if a repository exists
     */
    public static function repositoryExists(string $repoName): bool
    {
        try {
            $response = Http::withHeaders(self::getHeaders())
                ->get("https://api.github.com/repos/" . self::$organization . "/{$repoName}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Exception while checking repository existence', [
                'repo_name' => $repoName,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get repository information
     */
    public static function getRepository(string $repoName): ?array
    {
        try {
            $response = Http::withHeaders(self::getHeaders())
                ->get("https://api.github.com/repos/" . self::$organization . "/{$repoName}");

            if (!$response->successful()) {
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception while getting repository information', [
                'repo_name' => $repoName,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
