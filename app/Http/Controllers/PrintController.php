<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PrintController extends Controller
{

    public function printtiket(Request $request)
    {
        if (Auth::check()) {
            $acara = $request->query('acara');
            $peserta = $request->query('peserta');

            $acara = Event::find($acara);
            $partisipan = Participant::where( 'user_id', $peserta)->get();
            $pembayaran = Payment::where( 'user_id', $peserta)->get();
            $data = [
                'date' => date('d/m/Y'),
                'acara' => $acara,
                'partisipan' => $partisipan,
                'pembayaran' => $pembayaran,
                'url' => 'printtiket?'.'acara='.$acara->id.'&peserta='.$partisipan[0]->user_id,
            ];
            return view('print-tiket', $data);
        }
    }
}
