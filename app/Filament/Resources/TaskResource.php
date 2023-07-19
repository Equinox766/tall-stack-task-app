<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';


    protected static ?string $navigationGroup = 'Tasks';
    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Select a user')
                    ->required(),
                Forms\Components\Select::make('task_group_id')
                    ->label('Select a task group')
                    ->relationship('taskGroup', 'title')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->sortable()
                    ->searchable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('taskGroup.title')
                    ->colors([
                        'secondary' => 'Testing',
                        'primary' => 'Backlog',
                        'warning' => 'In Progress',
                        'success' => 'Done',
                        'danger' => 'To Do'
                    ])
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/y H:i')
                    ->label('Created'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->searchable()
                    ->relationship('user', 'name'),
                Tables\Filters\SelectFilter::make('taskGroup')
                    ->searchable()
                    ->multiple()
                    ->relationship('taskGroup', 'title'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTasks::route('/'),
        ];
    }
}
