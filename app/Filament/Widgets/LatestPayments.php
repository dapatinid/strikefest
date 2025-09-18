<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPayments extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Payment::query())
            ->columns([
                TextColumn::make('date_payment'),
                TextColumn::make('notes')
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('nominal')
                    ->searchable()
                    ->sortable()
                    ->numeric(locale: 'id')->prefix('Rp ')
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Total'))
                    ->alignRight(),
                TextColumn::make('user.name')
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
