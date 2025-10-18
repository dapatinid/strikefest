<?php

namespace App\Filament\Resources\Participants\Tables;

use App\Models\Participant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('participantable_id')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('participantable_type')
                //     ->searchable(),
                // TextColumn::make('team')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('participantable.title')->label('Event')->searchable()->sortable(),
                ImageColumn::make('user.image')->disk('public')->circular()->label('Avatar'),
                TextColumn::make('user.name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('userTeam.klub')
                ->label('Nama Tim')
                ->searchable(),
                TextColumn::make('name_emergency')
                    ->searchable(),
                TextColumn::make('relation_emergency')
                    ->searchable(),
                TextColumn::make('phone_emergency')
                    ->searchable(),
                TextColumn::make('userCre.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('userUpd.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        fn (Participant $record) => route('printtiket', [
                            'acara' => $record->participantable_id,
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
                if ($record->participantable_type === 'App\Models\Event') {
                    return url("/copanel/events/{$record->participantable_id}/participants?search={$record->user->name}");
                } else {
                    return null;
                }
            });
    }
}
