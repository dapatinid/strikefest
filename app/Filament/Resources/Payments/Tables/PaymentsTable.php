<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Models\Payment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('paymentable.title')->label('Event')->searchable()->sortable(),
                TextColumn::make('date_payment')->sortable(),
                ImageColumn::make('image')->disk('public'),
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
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
                EditAction::make('print')
                    ->label('Print Tiket')
                    ->hiddenLabel()
                    ->url(
                        fn (Payment $record) => route('printtiket', [
                            'acara' => $record->paymentable_id,
                            'peserta' => $record->user_id,
                        ])
                    )
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer'),
            ], position: RecordActionsPosition::BeforeColumns)
            // ->toolbarActions([
            //     BulkActionGroup::make([
            //         DeleteBulkAction::make(),
            //         ForceDeleteBulkAction::make(),
            //         RestoreBulkAction::make(),
            //     ]),
            // ])
            ->recordUrl(function ($record) {
                if ($record->paymentable_type === 'App\Models\Event') {
                    return url("/copanel/events/{$record->paymentable_id}/payments?search={$record->user->name}");
                } else {
                    return null;
                }
            });
    }
}
