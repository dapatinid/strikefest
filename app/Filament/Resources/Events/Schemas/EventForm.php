<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Models\Event;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Str;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([


                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('subtitle')
                    ->maxLength(255),
                TextInput::make('slug')
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    // ->readOnly(fn($record) => !is_null($record)) # tidak dapat diedit setelah terisi
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                    ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'edit' ? $set('slug', Str::slug($state)) : null)
                    ->afterStateUpdated(
                        function (Get $get, $state, Set $set) {
                            // Query Count
                            $jumlahslug_cek = Event::where('slug', $get('slug'))->count();
                            if ($state != null && $jumlahslug_cek > 0) {
                                $set('slug', null);
                                Notification::make()
                                    ->title('Slug sudah digunakan. Ganti yang lain!')
                                    ->icon('heroicon-o-x-circle')
                                    ->iconColor('danger')
                                    ->send();
                            }
                        },
                    ),

                FileUpload::make('cover')
                    ->image()
                    ->imageEditor()
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory(function (Get $get) {
                        return 'events/' . Auth::user()->id . '/' . $get('date_created');
                    }),

                RichEditor::make('body')
                    ->fileAttachmentsDirectory(function (Get $get) {
                        return 'events/' . Auth::user()->id . '/' . $get('date_created');
                    })
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Harga Tiket')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix('Rp')
                    ->columnSpanFull(),

                TagsInput::make('embed_videos')
                    ->separator(',')
                    ->reorderable()
                    ->columnSpanFull(),

                TagsInput::make('categories')
                    ->separator(',')
                    ->reorderable(),
                TagsInput::make('tags')
                    ->separator(',')
                    ->reorderable(),

                DateTimePicker::make('date_from'),
                DateTimePicker::make('date_until'),
                DateTimePicker::make('date_published'),

                TextInput::make('date_created')
                    ->default(Carbon::now()->format('Ymd-His'))
                    ->readOnly(fn($record) => !is_null($record))
                    ->required(),
                Hidden::make('created_by')
                    ->default(fn() => Auth::user()->id),
                Hidden::make('updated_by')
                    ->default(fn() => Auth::user()->id),


            ]);
    }
}
