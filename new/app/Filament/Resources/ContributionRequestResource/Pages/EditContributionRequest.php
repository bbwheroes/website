<?php

namespace App\Filament\Resources\ContributionRequestResource\Pages;

use App\Filament\Resources\ContributionRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContributionRequest extends EditRecord
{
    protected static string $resource = ContributionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
