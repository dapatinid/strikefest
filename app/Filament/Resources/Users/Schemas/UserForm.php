<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->maxSize(4000)
                    ->disk('public')
                    ->directory('avatar')
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->live()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
                    // ->prefixAction(
                    //     Forms\Components\Actions\Action::make('toggle-password-visibility')
                    //         ->icon('heroicon-o-eye')
                    //         ->iconSize('md')
                    //         ->action(function ($component) {
                    //             $component->type('text');
                    //         })
                    // )
                    ->suffixAction(
                        Action::make('toggle-password-visibility')
                            ->icon('heroicon-o-eye')
                            ->iconSize('md')
                            ->action(function ($component) {
                                $component->type('text');
                            })
                        // Forms\Components\Actions\Action::make('toggle-password-invisibility')
                        //     ->icon('heroicon-o-eye-slash')
                        //     ->iconSize('md')
                        //     ->action(function ($component) {
                        //         $component->type('password');
                        //     })
                    ),
                Toggle::make('is_admin')
                    ->required(),
                TextInput::make('level'),

                TextInput::make('phone')
                    ->tel()
                    ->maxLength(20),

                TextInput::make('zip_code')
                    ->numeric()
                    ->maxLength(10),

                Select::make('state')
                    ->options(Province::query()->pluck('name', 'code'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('city', null);
                    }),

                Select::make('city')
                    ->options(function (Get $get): Collection {
                        return City::query()->orderBy('name', 'ASC')->where('province_code', $get('state'))->pluck('name', 'code');
                    })
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('district', null);
                    }),

                Select::make('district')
                    ->options(function (Get $get): Collection {
                        return District::query()->orderBy('name', 'ASC')->where('city_code', $get('city'))->pluck('name', 'code');
                    })
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('village', null);
                    }),

                Select::make('village')
                    ->options(function (Get $get): Collection {
                        return Village::query()->orderBy('name', 'ASC')->where('district_code', $get('district'))->pluck('name', 'code');
                    })
                    ->searchable(),

                Textarea::make('street')
                    ->columnSpanFull(),

                FileUpload::make('image_id')
                    ->image()
                    ->imageEditor()
                    ->maxSize(4000)
                    ->disk('public')
                    ->directory('image_id'),
                TextInput::make('no_id')
                    ->required(),

                TextInput::make('tempat_lahir')
                    ->required(),
                DatePicker::make('tanggal_lahir')
                    ->required(),
                Select::make('gender')
                        ->options([
                                    'L' => 'Laki - Laki',
                                    'P' => 'Perempuan',
                                ])
                        ->required(),
                Select::make('ukuran_jersey')
                    ->options([
                                'S' => 'S',
                                'M' => 'M',
                                'L' => 'L',
                                'XL' => 'XL',
                                'XXL' => 'XXL',
                                'XXXL' => 'XXXL',
                            ])
                    ->required(),
                TextInput::make('klub'),


                TextInput::make('poin')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
