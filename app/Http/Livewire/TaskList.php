<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Livewire\Component;

class TaskList extends Component implements HasTable
{
    use InteractsWithTable;

    public function render()
    {
        return view('livewire.task-list');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Task::query()->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->sortable()
                ->searchable(),
            TextColumn::make('description')
                ->sortable()
                ->searchable(),
            BadgeColumn::make('taskGroup.title')
                ->colors([
                    'secondary' => 'Testing',
                    'primary' => 'Backlog',
                    'warning' => 'In Progress',
                    'success' => 'Done',
                    'danger' => 'To Do'
                ])
                ->sortable()
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime('d/m/y H:i')
                ->label('Created'),
        ];
    }



}

