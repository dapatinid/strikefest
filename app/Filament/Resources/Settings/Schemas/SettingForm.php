<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('website_name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default('0'),
                RichEditor::make('info_event')
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('panduan_lomba')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
