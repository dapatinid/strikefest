<x-guest-layout>
    <div class="my-6 flex items-center justify-center">
        <img src="{{ asset('/assets/images/LogoStrikefest2025.png') }}" class="size-56"/>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="space-y-4">
            {{-- <x-input label="Email *" type="email" name="email" :value="old('email', 'test@example.com')" required autofocus autocomplete="username" /> --}}
            <x-input label="No. Whatsapp *" type="number" name="phone"  required autofocus autocomplete="phone" />

            <x-password label="Password *" type="password" name="password" required autocomplete="current-password" />
        </div>

        <div class="block mt-4">
            <x-checkbox label="Remember me" id="remember_me" type="checkbox" name="remember" />
        </div>

        <div class="flex items-center justify-between mt-4">
                <div class="flex items-center justify-start">
                    <a href="https://wa.me/62081325171106?text=Mohon%20bantu%20saya%2C%20saya%20telah%20lupa%20password.%0ANama%20%3A%0Aemail%20%3A" 
                    {{-- href="https://wa.me/62{{ App\Models\Setting::get()->first()->phone ?? 0 }}"  --}}
                        target="_blank">Lupa Password?</a>
                </div>
                <div class="flex items-center justify-end">
                    @if (Route::has('register'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md" href="{{ route('register') }}">
                            {{ __('Daftar') }}
                        </a>
                    @endif

                    <x-button type="submit" class="ms-3">
                        {{ __('Masuk') }}
                    </x-button>
                </div>
        </div>
    </form>
</x-guest-layout>
