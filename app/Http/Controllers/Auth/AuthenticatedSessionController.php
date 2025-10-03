<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->street == null || Auth::user()->village == null || Auth::user()->district == null || Auth::user()->city == null || Auth::user()->state == null) {
            return redirect()->intended(route('user.profilealamat', absolute: false));
        } elseif (Auth::user()->image == null || Auth::user()->gender == null || Auth::user()->tempat_lahir == null || Auth::user()->tanggal_lahir == null || Auth::user()->image_id == null || Auth::user()->no_id == null || Auth::user()->ukuran_jersey == null) {
            return redirect()->intended(route('user.profiledatadiri', absolute: false));
        } else {
            return redirect()->intended(route('home', absolute: false));
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
