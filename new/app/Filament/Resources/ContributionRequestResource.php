<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContributionRequestResource\Pages;
use App\Models\ContributionRequest;
use App\Models\Project;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class ContributionRequestResource extends Resource
{
    protected static ?string $model = ContributionRequest::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationLabel = 'Contribution Requests';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Request Details')
                    ->schema([
                        Forms\Components\TextInput::make('module')
                            ->label('Module Number')
                            ->disabled()
                            ->maxLength(3),
                        Forms\Components\TextInput::make('teacher')
                            ->label('Teacher')
                            ->disabled()
                            ->maxLength(4),
                        Forms\Components\TextInput::make('task_name')
                            ->label('Task Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('slugified_task_name')
                            ->label('Slugified Task Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('github_username')
                            ->label('GitHub Username')
                            ->disabled(),
                        Forms\Components\TagsInput::make('collaborators')
                            ->label('Collaborators')
                            ->disabled(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Admin Actions')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'declined' => 'Declined',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
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
                Tables\Actions\Action::make('accept')
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
                        self::sendDiscordNotification($record, 'accepted');

                        Notification::make()
                            ->title('Request Accepted')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('decline')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ContributionRequest $record) => $record->status === 'pending')
                    ->action(function (ContributionRequest $record) {
                        $record->update(['status' => 'declined']);

                        // Send Discord notification
                        self::sendDiscordNotification($record, 'declined');

                        Notification::make()
                            ->title('Request Declined')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function sendDiscordNotification(ContributionRequest $request, string $action): void
    {
        $webhookUrl = config('services.discord.webhook_url');

        if (!$webhookUrl) {
            return;
        }

        $color = $action === 'accepted' ? 3066993 : 15158332; // Green or Red
        $adminUrl = route('filament.admin.resources.contribution-requests.view', ['record' => $request->id]);

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContributionRequests::route('/'),
            'view' => Pages\ViewContributionRequest::route('/{record}'),
            'edit' => Pages\EditContributionRequest::route('/{record}/edit'),
        ];
    }
}
