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
            Actions\EditAction::make(),
        ];
    }
}
