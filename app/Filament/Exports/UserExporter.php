<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('phone'),
            ExportColumn::make('email'),
            ExportColumn::make('email_verified_at'),
            ExportColumn::make('is_admin')
                ->formatStateUsing(fn (string $state): string => $state == 1 ? "Admin": "End User"),
            // ExportColumn::make('level'),
            ExportColumn::make('gender'),
            ExportColumn::make('tempat_lahir'),
            ExportColumn::make('tanggal_lahir'),
            ExportColumn::make('no_id'),
            ExportColumn::make('ukuran_jersey'),
            ExportColumn::make('klub'),
            ExportColumn::make('street'),
            ExportColumn::make('desa.name'),
            ExportColumn::make('kec.name'),
            ExportColumn::make('kabkota.name'),
            ExportColumn::make('prov.name'),
            // ExportColumn::make('village'),
            // ExportColumn::make('district'),
            // ExportColumn::make('city'),
            // ExportColumn::make('state'),
            // ExportColumn::make('zip_code'),
            ExportColumn::make('image'),
            ExportColumn::make('image_id'),
            // ExportColumn::make('poin'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
