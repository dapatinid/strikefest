<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Models\User;
use BackedEnum;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Payments extends ManageRelatedRecords
{
    protected static string $resource = EventResource::class;

    protected static string $relationship = 'Payments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->image()
                    ->imageEditor()
                    ->maxSize(1024)
                    ->disk('public')
                    ->directory('payments'),
                Textarea::make('notes')
                    ->label('catatan'),
                ToggleButtons::make('payment_method')
                    ->disabled(function (Get $get) {
                        if (Auth::user()->level !== "backofficer" && $get('id') != null) {
                            return true;
                        }
                    })
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                    ])
                    ->required()
                    ->grouped()
                    ->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                    ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id)),
                TextInput::make('nominal_plus')
                    ->label('Nominal Bayar')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix('Rp'),
                Select::make('user_id')
                    ->label('oleh')
                    ->options(User::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),


                Hidden::make('date_payment')
                    ->default(now()),
                Hidden::make('mutation_type')
                    ->default('Event'),
                Hidden::make('currency')
                    ->default('idr'),
                Hidden::make('created_by')
                    ->default(fn() => Auth::user()->id),
                Hidden::make('updated_by')
                    ->default(fn() => Auth::user()->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Payment')
            ->columns([
                TextColumn::make('date_payment'),
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
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
