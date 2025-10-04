<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Exports\UserExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(UserExporter::class),
            ])
            ->columns([
                ImageColumn::make('image')->disk('public')->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                // TextColumn::make('email_verified_at')
                //     ->dateTime()
                //     ->sortable(),
                IconColumn::make('is_admin')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('level')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                // TextColumn::make('village')
                //     ->searchable(),
                // TextColumn::make('district')
                //     ->searchable(),
                // TextColumn::make('city')
                //     ->searchable(),
                // TextColumn::make('state')
                //     ->searchable(),
                // TextColumn::make('zip_code')
                //     ->searchable(),
                // TextColumn::make('poin')
                //     ->numeric()
                //     ->sortable(),
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
