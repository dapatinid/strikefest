<?php

namespace App\Livewire;

use App\Livewire\Traits\Alert;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class TicketPage extends Component
{
    use Alert, WithPagination;        

    #[Title('Ticket')]
    public function render()
    {
        $event = Event::whereHas('participants', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->orderby('date_from')->paginate(10);
        return view('livewire.ticket-page', [
            'event' => $event,
        ])
            // ->title(Event::find($this->id)->title) // custom title
        ;
    }

}
