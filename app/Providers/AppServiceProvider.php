<?php

namespace App\Providers;

use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\ServiceProvider;
// use TallStackUi\Facades\TallStackUi;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        // Set default timezone for DateTimePicker
        DateTimePicker::configureUsing(function (DateTimePicker $component): void {
            $component->timezone(auth()->user()?->timezone ?? config('app.timezone'));
        });

        // Set default timezone for TextColumn for datetime attibutes
        TextColumn::configureUsing(function (TextColumn $component): void {
            if (in_array($component->getName(), ['created_at', 'updated_at', 'published_at', 'email_verified_at', 'date_payment'])) {
                $component->timezone(auth()->user()?->timezone ?? config('app.timezone'));
            }
        });

        // TallStackUi::personalize()
        //     ->form('input')
        //     ->block('input.wrapper')
        //     ->replace([
        //         'rounded-md' => 'rounded-full',
        //         'border' => 'border-5',
        //         'shadow-sm' => 'shadow-lg',
        //     ]);
    }
}
