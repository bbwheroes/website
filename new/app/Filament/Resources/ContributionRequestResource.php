<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContributionRequestResource\Pages;
use App\Helpers\DiscordHelper;
use App\Models\ContributionRequest;
use App\Models\Project;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Forms\Components;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class ContributionRequestResource extends Resource
{
    protected static ?string $model = ContributionRequest::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationLabel = 'Contribution Requests';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('module')
                    ->label('Module Number')
                    ->disabled()
                    ->maxLength(3),
                Components\TextInput::make('teacher')
                    ->label('Teacher')
                    ->disabled()
                    ->maxLength(4),
                Components\TextInput::make('task_name')
                    ->label('Task Name')
                    ->disabled(),
                Components\TextInput::make('slugified_task_name')
                    ->label('Slugified Task Name')
                    ->disabled(),
                Components\TextInput::make('github_username')
                    ->label('GitHub Username')
                    ->disabled(),
                Components\TagsInput::make('collaborators')
                    ->label('Collaborators')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('module')
                    ->label('Module')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('task_name')
                    ->label('Task')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('github_username')
                    ->label('GitHub User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'declined',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                    ])
                    ->default('pending'),
            ])
            ->actions([
                Actions\Action::make('accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (ContributionRequest $record) => $record->status === 'pending')
                    ->action(function (ContributionRequest $record) {
                        $record->update(['status' => 'accepted']);

                        // Create project
                        Project::create([
                            'module' => $record->module,
                            'teacher' => $record->teacher,
                            'task_name' => $record->task_name,
                            'slugified_task_name' => $record->slugified_task_name,
                            'username' => $record->github_username,
                            'approved' => true,
                        ]);

                        // Send Discord notification
                        DiscordHelper::sendRequestStatusNotification($record, 'accepted');

                        Notification::make()
                            ->title('Request Accepted')
                            ->success()
                            ->send();
                    }),
                Actions\Action::make('decline')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ContributionRequest $record) => $record->status === 'pending')
                    ->action(function (ContributionRequest $record) {
                        $record->update(['status' => 'declined']);

                        // Send Discord notification
                        DiscordHelper::sendRequestStatusNotification($record, 'declined');

                        Notification::make()
                            ->title('Request Declined')
                            ->success()
                            ->send();
                    }),
                Actions\ViewAction::make(),
                ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContributionRequests::route('/'),
            'view' => Pages\ViewContributionRequest::route('/{record}'),
        ];
    }
}
