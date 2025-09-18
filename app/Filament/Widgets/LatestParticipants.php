<?php

namespace App\Filament\Widgets;

use App\Models\Participant;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestParticipants extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Participant::query())
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('name_emergency')
                    ->searchable(),
                TextColumn::make('relation_emergency')
                    ->searchable(),
                TextColumn::make('phone_emergency')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
