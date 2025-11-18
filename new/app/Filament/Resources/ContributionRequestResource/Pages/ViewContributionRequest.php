<?php

namespace App\Filament\Resources\ContributionRequestResource\Pages;

use App\Filament\Resources\ContributionRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContributionRequest extends ViewRecord
{
    protected static string $resource = ContributionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('accept')
                ->label('Accept Request')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'pending')
                ->action(function () {
                    $this->record->update(['status' => 'accepted']);

                    // Create project
                    Project::create([
                        'module' => $this->record->module,
                        'teacher' => $this->record->teacher,
                        'task_name' => $this->record->task_name,
                        'slugified_task_name' => $this->record->slugified_task_name,
                        'username' => $this->record->github_username,
                        'approved' => true,
                    ]);

                    // Send Discord notification
                    $this->sendDiscordNotification($this->record, 'accepted');

                    Notification::make()
                        ->title('Request Accepted')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.contribution-requests.index');
                }),
            Actions\Action::make('decline')
                ->label('Decline Request')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'pending')
                ->action(function () {
                    $this->record->update(['status' => 'declined']);

                    // Send Discord notification
                    $this->sendDiscordNotification($this->record, 'declined');

                    Notification::make()
                        ->title('Request Declined')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.contribution-requests.index');
                }),
        ];
    }

    protected function sendDiscordNotification(ContributionRequest $request, string $action): void
    {
        $webhookUrl = config('services.discord.webhook_url');

        if (!$webhookUrl) {
            return;
        }

        $color = $action === 'accepted' ? 3066993 : 15158332; // Green or Red
        $adminUrl = route('filament.admin.resources.contribution-requests.edit', ['record' => $request->id]);

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
                    'value' => $request->github_username,
                    'inline' => false,
                ],
                [
                    'name' => 'Admin Panel',
                    'value' => "[View Details]({$adminUrl})",
                    'inline' => false,
                ],
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        Http::post($webhookUrl, [
            'username' => 'BBW Heroes Admin',
            'embeds' => [$embed],
        ]);
    }
}
