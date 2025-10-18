<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ParticipantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('participantable_id')
                //     ->numeric(),
                // TextEntry::make('participantable_type'),
                // TextEntry::make('team')
                //     ->numeric(),
                // TextEntry::make('name_emergency'),
                // TextEntry::make('relation_emergency'),
                // TextEntry::make('phone_emergency'),
                // TextEntry::make('user_id')
                //     ->numeric(),
                // TextEntry::make('created_by')
                //     ->numeric(),
                // TextEntry::make('updated_by')
                //     ->numeric(),
                // TextEntry::make('created_at')
                //     ->dateTime(),
                // TextEntry::make('updated_at')
                //     ->dateTime(),
                // TextEntry::make('deleted_at')
                //     ->dateTime(),
            ]);
    }
}
