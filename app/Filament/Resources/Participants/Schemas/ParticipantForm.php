<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('participantable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('participantable_type')
                    ->required(),
                TextInput::make('team')
                    ->numeric(),
                TextInput::make('name_emergency'),
                TextInput::make('relation_emergency'),
                TextInput::make('phone_emergency')
                    ->tel()
                    ->default('0'),
                Textarea::make('health_verification')
                    ->columnSpanFull(),
                Textarea::make('statement_of_agreement')
                    ->columnSpanFull(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('created_by')
                    ->numeric(),
                TextInput::make('updated_by')
                    ->numeric(),
            ]);
    }
}
