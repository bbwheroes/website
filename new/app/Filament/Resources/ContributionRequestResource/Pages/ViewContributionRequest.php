<?php

namespace App\Filament\Resources\ContributionRequestResource\Pages;

use App\Filament\Resources\ContributionRequestResource;
use App\Helpers\DiscordHelper;
use App\Models\Project;
use Filament\Actions;
use Filament\Notifications\Notification;
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
                    DiscordHelper::sendRequestStatusNotification($this->record, 'accepted');

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
                ->visible(fn () => $this->record->status === 'pending')
                ->action(function () {
                    $this->record->update(['status' => 'declined']);

                    // Send Discord notification
                    DiscordHelper::sendRequestStatusNotification($this->record, 'declined');

                    Notification::make()
                        ->title('Request Declined')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.contribution-requests.index');
                }),
        ];
    }
}
