<?php

use App\Http\Middleware\IsAdmin;
use App\Livewire\EventDetailPage;
use App\Livewire\TicketPage;
use App\Livewire\User\Profile;
use App\Livewire\User\ProfileDataDiri;
use App\Livewire\User\ProfileAlamat;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Index;
use App\Livewire\Users\Edit;
use App\Livewire\Users\EditAlamat;
use Illuminate\Support\Facades\Auth;

Route::view('/', 'home')->name('home');
Route::get('/event/{slug}', EventDetailPage::class)->name('event');
Route::view('/about', 'about')->name('about');

Route::middleware(['auth'])->group(function () {

    Route::get('/user/profile', Profile::class)->name('user.profile');
    Route::get('/user/profile-data-diri', ProfileDataDiri::class)->name('user.profiledatadiri');
    Route::get('/user/profile-alamat', ProfileAlamat::class)->name('user.profilealamat');

    Route::get('/ticket', TicketPage::class)->name('ticket');

    Route::middleware(IsAdmin::class)->group(function () {
        Route::get('/users', Index::class)->name('users.index');
        Route::get('/users/{userid}/edit', Edit::class)->name('users.edit');
        Route::get('/users/{userid}/edit-alamat', EditAlamat::class)->name('users.editalamat');
    });
});

require __DIR__ . '/auth.php';
