<?php

namespace App\Livewire;

use App\Livewire\Traits\Alert;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class HomePage extends Component
{
    use Alert, WithPagination;        

    #[Title('Karimunjawa StrikeFest 2025')]
    public function render()
    {
        $setting = Setting::get()->first() ;

        $event = Event::orderby('date_from')->paginate(10);
        
        $myTicket = (Auth::check()) ? 
            Event::whereHas('participants', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->count()
        : 0 ;
              
        if (Gallery::query()->where('is_active',true)->where('target','gallery')->get()->count() >= 3 ) {
            $galeri = Gallery::query()->where('is_active',true)->where('target','gallery')->get()->split(3);
        } elseif (Gallery::query()->where('is_active',true)->where('target','gallery')->get()->count() == 2) {
            $galeri = Gallery::query()->where('is_active',true)->where('target','gallery')->get()->split(2);
        } elseif (Gallery::query()->where('is_active',true)->where('target','gallery')->get()->count() <= 1) {
            $galeri = Gallery::query()->where('is_active',true)->where('target','gallery')->get()->split(1);
        }
        // dd($galeri);
        

        $sponsorship = Gallery::query()->where('is_active',true)->where('target','sponsorship')->get();

        return view('livewire.home-page', [
            'event' => $event,
            'myTicket' => $myTicket,
            'sponsorship' => $sponsorship,
            'galeri_1' => $galeri[0] ?? null,
            'galeri_2' => $galeri[1] ?? null,
            'galeri_3' => $galeri[2] ?? null,
            'setting' => $setting ?? null,
        ])
            // ->title(Event::find($this->id)->title) // custom title
        ;
    }

}
