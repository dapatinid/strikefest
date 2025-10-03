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
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Participants extends ManageRelatedRecords
{
    protected static string $resource = EventResource::class;

    protected static string $relationship = 'Participants';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('user_id')
                    ->label('Partisipan')
                    ->options(User::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('team')
                    ->label('Tim')
                    ->searchable()
                    ->preload()
                    ->relationship(
                        name: 'user',
                        modifyQueryUsing: fn(Builder $query) => $query->orderBy('klub')->whereNotNull('klub'),
                    )
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->klub} ~ ketua : {$record->name}"),
                TextInput::make('name_emergency')
                    ->label('Nama Darurat Utk Dihubungi')
                    ->required(),
                TextInput::make('relation_emergency')
                    ->label('Hubungan dengan Peserta')
                    ->required(),
                TextInput::make('phone_emergency')
                    ->label('Kontak HP / WA Darurat')
                    ->required()
                    ->tel()
                    ->columnSpanFull(),

                CheckboxList::make('health_verification')
                    ->options([
                        'sehat_jasmani_rohani' => 'Jasmani Rohani',
                        'sehat_jantung' => 'Jantung',
                        'tanpa_epilepsi' => 'Kejang',
                        'sehat_pernafasan' => 'Pernafasan',
                        'tidak_dalam_perawatan' => 'Tidak Dirawat',
                        'mampu_berenang' => 'Renang',
                        'surat_sehat' => 'Surat Sehat',
                    ])
                    ->descriptions([
                        'sehat_jasmani_rohani' => 'Saya dalam kondisi sehat jasmani dan rohani.',
                        'sehat_jantung' => 'Saya tidak memiliki riwayat penyakit jantung.',
                        'tanpa_epilepsi' => 'Saya tidak memiliki riwayat epilepsi / kejang.',
                        'sehat_pernafasan' => 'Saya tidak memiliki riwayat asma kronis / gangguan pernapasan berat.',
                        'tidak_dalam_perawatan' => 'Saya tidak sedang dalam perawatan medis yang dapat membahayakan saat mengikuti kegiatan laut (misal: operasi baru, terapi intensif, dll).',
                        'mampu_berenang' => 'Saya mampu berenang / memiliki keterampilan dasar keselamatan di air.',
                        'surat_sehat' => 'Saya bersedia menunjukkan surat keterangan sehat dari dokter bila diminta panitia.',
                    ]),

                CheckboxList::make('statement_of_agreement')
                    ->options([
                        'info_dipertanggungjawabkan' => 'Dipertanggungjawabkan',
                        'sehat_dan_siap' => 'Sehat dan Siap',
                        'bebas_tuntutan' => 'Panitia Bebas Tuntutan',
                    ])
                    ->descriptions([
                        'info_dipertanggungjawabkan' => 'Seluruh data diri dan informasi kesehatan yang saya berikan adalah benar dan dapat dipertanggungjawabkan.',
                        'sehat_dan_siap' => 'Saya dalam kondisi sehat dan siap mengikuti Karimunjawa StrikeFest 2025.',
                        'bebas_tuntutan' => 'Saya bertanggung jawab penuh atas kondisi kesehatan pribadi saya, serta membebaskan panitia dari tuntutan hukum/medis bila terjadi hal-hal di luar kendali panitia.',
                    ]),


                Hidden::make('created_by')
                    ->default(fn() => Auth::user()->id),
                Hidden::make('updated_by')
                    ->default(fn() => Auth::user()->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Participant')
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('userTeam.klub')
                ->label('Nama Tim')
                ->searchable(),
                // ->formatStateUsing(fn ($state) => "ID Team : " . $state . " | Nama Team : " .User::find($state)->klub)
                //     ->sortable()
                //     ->searchable(query: function (Builder $query, string $search): Builder {
                //         return $query
                //             ->where('team', 'like', "%{$search}%");
                //     }),
                TextColumn::make('name_emergency')
                    ->searchable(),
                TextColumn::make('relation_emergency')
                    ->searchable(),
                TextColumn::make('phone_emergency')
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
