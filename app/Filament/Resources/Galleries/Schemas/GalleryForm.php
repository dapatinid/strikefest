<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Grid::make()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                // ->readOnly(fn ($record) => !is_null($record)) membuat tidak dapat diedit
                                ->maxLength(255),

                            Select::make('target')
                                ->required()
                                ->options([
                                    'gallery' => 'Gallery',
                                    'sponsorship' => 'Sponsorship',
                                    // 'hero_section' => 'Hero Section'
                                ])
                        ]),

                    TextInput::make('link')
                        ->default('#')
                        ->maxLength(255),

                    FileUpload::make('image')
                        ->image()
                        ->imageEditor()
                        ->maxSize(4000)
                        ->disk('public')
                        ->directory(function (Get $get) {
                            return 'gallery/' . $get('target');
                        }),

                    Toggle::make('is_active')
                        ->required()
                        ->default(true)
            ]);
    }
}
