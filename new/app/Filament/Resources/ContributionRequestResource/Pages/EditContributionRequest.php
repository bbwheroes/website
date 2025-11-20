<?php

namespace App\Filament\Resources\ContributionRequestResource\Pages;

use App\Filament\Resources\ContributionRequestResource;
use App\Helpers\DiscordHelper;
use App\Helpers\GitHubHelper;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditContributionRequest extends EditRecord
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
                    try {
                        // Create GitHub repository and invite collaborators
                        GitHubHelper::setupRepositoryForRequest($this->record);

                        // Update request status
                        $this->record->update(['status' => 'accepted']);

                        // Send Discord notification
                        DiscordHelper::sendRequestStatusNotification($this->record, 'accepted');

                        Notification::make()
                            ->title('Request Accepted')
                            ->body('Repository created and collaborators invited successfully.')
                            ->success()
                            ->send();

                        return redirect()->route('filament.admin.resources.contribution-requests.index');
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error Accepting Request')
                            ->body('Failed to create GitHub repository: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
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
