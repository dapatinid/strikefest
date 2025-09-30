<div class="">
  <!-- NAV -->
  <header class="{{ request()->is('/') ? 'fixed' : 'hidden' }}  top-0 left-0 right-0 z-40 bg-white/90 shadow">
    <nav class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('/assets/images/LogoStrikefest2025.png') }}" alt="Logo StrikeFest" class="w-12 h-12">
        <h1 class="font-bold text-lg text-sky-700">Karimunjawa StrikeFest 2025</h1>
      </div>
      <div class="flex items-center gap-6 text-sm font-medium">
        <a href="#info" class="hidden md:flex hover:text-sky-700">Informasi Event</a>
        <a href="#panduan" class="hidden md:flex hover:text-sky-700">Panduan Lomba</a>
        <a href="#galeri" class="hidden md:flex hover:text-sky-700">Galeri</a>
        <a href="#sponsor" class="hidden md:flex hover:text-sky-700">Sponsor</a>
        <a href="https://wa.me/62{{ $setting->phone ?? 0 }}" target="_blank" class="hidden md:flex hover:text-sky-700">Live Chat</a>
        @guest            
        <a href="/login" class="font-bold px-3 py-1 rounded-md bg-blue-300 hover:text-sky-700">LOGIN</a>
        @endguest
        @auth
        <a href="/ticket" class="font-bold px-3 py-1 rounded-md bg-blue-300 hover:text-sky-700">TIKET <span class="{{ $myTicket > 0 ? 'absolute' : 'hidden'}} p-1 text-white bg-sky-600 rounded-full -mt-4">{{ $myTicket }}</span></a>    
        @endauth
      </div>
    </nav>
  </header>

  <!-- HERO -->
  <section class="pt-20 relative -mt-1.5">
    <div class="hero-bg h-[70vh] flex flex-col items-center justify-center text-center" style="background-image:url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=60')">
      <div class="p-8 glass rounded-2xl">
        <h2 class="text-4xl md:text-6xl font-extrabold text-sky-900">Karimunjawa StrikeFest 2025</h2>
        <p class="mt-4 text-lg md:text-xl text-sky-800">14–16 November 2025 | Pulau Karimunjawa</p>
        <div id="countdown" class="mt-6 text-2xl font-bold text-sky-900"></div>
        <a href="#daftar" class="mt-6 inline-block px-6 py-3 bg-sky-700 text-white font-semibold rounded-xl shadow hover:bg-sky-800">Daftar Sekarang</a>
      </div>
    </div>
  </section>

  <!-- Informasi Event -->
  <section id="info" class="max-w-6xl mx-auto py-16 px-4 [&>ul]:list-disc [&>ul]:ml-5">
    <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Informasi Event</h3>
    <p class="text-gray-700 leading-relaxed text-center max-w-3xl mx-auto">
        @php
            $paragraf = Str::replace('<blockquote>', '<blockquote class="relative border-s-4 border-green-500 dark:border-green-300 py-5 ps-4 sm:ps-6 bg-zinc-100 dark:bg-zinc-800 "><div class="relative z-10"><p class="text-gray-700 dark:text-white"><em>', $setting->info_event ?? null);
            $paragraf = Str::replace('</blockquote>', '</em></p></blockquote>', $paragraf);
        @endphp
        {!! Str::markdown(str($paragraf)->sanitizeHtml()) !!}
    </p>
  </section>

  <!-- Pendaftaran -->
  <section id="daftar" class="bg-white py-16">
    <div class="max-w-4xl mx-auto px-4">
      @guest
      <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Daftar atau Login</h3>
      @endguest
      @auth
      <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Info Tiket</h3>
      @endauth
      <div class="flex justify-center space-x-3">
          @guest              
            <a href="{{ url('/register') }}">
              <button class="px-6 py-3 bg-sky-700 text-white rounded-xl font-semibold hover:bg-sky-800">Kirim Pendaftaran</button>
            </a>
            <a href="{{ url('/login') }}">
              <button class="px-6 py-3 bg-sky-500 text-white rounded-xl font-semibold hover:bg-sky-800">Login & Cek Tiket</button>
            </a>
          @endguest
          @auth              
            <a href="{{ url('/ticket') }}">
              <button class="px-6 py-3 bg-sky-500 text-white rounded-xl font-semibold hover:bg-sky-800 flex flex-nowrap gap-3"><span>Cek Status Ticket </span>
                <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
                </span>
              </button>
            </a>
          @endauth
      </div>
      <div class="grid md:grid-cols-3 grid-cols-1 mt-4 gap-3">
        @foreach ($event as $ev)
            <a wire:navigate.hover href="{{ route('event', ['slug' => $ev->slug]) }}" class="p-2 block bg-transparent">
                <div class="flex-none shadow-lg rounded-md relative text-white"><div class="font-bold text-2xl text-center text-shadow-lg/30 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 ">{{ Illuminate\Support\Str::upper($ev->title) }}</div><img src="{{ Str::replace('%2F', '/',url('storage', $ev->cover)) }}" alt="" class="w-full object-cover aspect-video rounded-md"></div>                
                <div class="w-auto shrink mt-10 bg-white border border-gray-100 shadow-lg p-3 rounded-md  space-y-2">
                    <div class="font-bold dark:text-white text-center -mt-7 mb-3 bg-sky-300 p-2 rounded-md">Rp{{ Number::format($ev->price, locale: 'id') }}</div>                    
                    <div class="text-xs dark:text-primary-200 h-7 text-center">{{ $ev->subtitle }} {{ $ev->categories }}</div>
                    
                    <div class="p-3 pb-10 [&>ul]:list-disc [&>ul]:ml-5 dark:text-gray-200 truncate line-clamp-6 min-h-80">
                        <p class="max-w-md ">
                            @php
                                $paragraf = Str::replace('<blockquote>', '<blockquote class="relative border-s-4 border-green-500 dark:border-green-300 py-5 ps-4 sm:ps-6 bg-zinc-100 dark:bg-zinc-800 "><div class="relative z-10"><p class="text-gray-700 dark:text-white"><em>', $ev->body);
                                $paragraf = Str::replace('</blockquote>', '</em></p></blockquote>', $paragraf);
                            @endphp
                            {!! Str::markdown(str($paragraf)->sanitizeHtml()) !!}
                        </p>
                    </div>

                    @if ($ev->tags != '')
                    <div>
                        @php
                            $tags = Str::of($ev->tags)->explode(',');
                        @endphp
                        @foreach ($tags as $tag)
                        <x-badge text="{{ $tag }}" color="lime" outline xs/>
                        @endforeach
                    </div>
                    @endif
                </div>
            </a>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Panduan Lomba -->
  <section id="panduan" class="max-w-6xl mx-auto py-16 px-4 ">
    <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Panduan Lomba</h3>
    <div class="[&>ul]:list-disc [&>ul]:ml-5 list-disc list-inside space-y-3 text-gray-700 max-w-3xl mx-auto">
      <p>
          @php
              $paragraf = Str::replace('<blockquote>', '<blockquote class="relative border-s-4 border-green-500 dark:border-green-300 py-5 ps-4 sm:ps-6 bg-zinc-100 dark:bg-zinc-800 "><div class="relative z-10"><p class="text-gray-700 dark:text-white"><em>', $setting->panduan_lomba ?? null);
              $paragraf = Str::replace('</blockquote>', '</em></p></blockquote>', $paragraf);
          @endphp
          {!! Str::markdown(str($paragraf)->sanitizeHtml()) !!}
      </p>
    </div>
  </section>

  <!-- Galeri -->
  <section id="galeri" class="bg-sky-50 py-16">
    <div class="max-w-6xl mx-auto px-4">
      <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Galeri Karimunjawa</h3>
      <div class="grid md:grid-cols-3 gap-6">
         @if ($galeri_1)
        <div class="block space-y-6">
            @foreach ($galeri_1 as $image)  
              <img src="{{ asset('storage/'.$image->image) }}" alt="Gallery" class="rounded-xl shadow">
            @endforeach             
        </div>
        @endif
         @if ($galeri_2)
        <div class="block space-y-6">
            @foreach ($galeri_2 as $image)  
              <img src="{{ asset('storage/'.$image->image) }}" alt="Gallery" class="rounded-xl shadow">
            @endforeach             
        </div>
        @endif
        @if ($galeri_3)
        <div class="block space-y-6">
            @foreach ($galeri_3 as $image)  
              <img src="{{ asset('storage/'.$image->image) }}" alt="Gallery" class="rounded-xl shadow">
            @endforeach             
        </div>
        @endif
      </div>
    </div>
  </section>

  <!-- Sponsor -->
  <section id="sponsor" class="bg-gray-100 py-16">
    <div class="max-w-6xl mx-auto px-4 text-center">
      <h3 class="text-3xl font-bold mb-8 text-sky-700">Sponsor & Media Partner</h3>
      <p class="text-gray-700 max-w-3xl mx-auto mb-8">Terima kasih kepada para sponsor yang mendukung Karimunjawa StrikeFest 2025. Kami membuka paket Platinum, Gold, Silver, dan Sponsor Pendukung untuk kerja sama promosi.</p>
      <div class="overflow-hidden relative w-full">
        <div class="sponsor-slider space-x-12">
          @if ($sponsorship->count() > 0)     
            @foreach ($sponsorship as $image)              
              <img src="{{ asset('storage/'.$image->image) }}" alt="sponsorship" class="h-20 object-contain">
            @endforeach         
            @foreach ($sponsorship as $image)              
              <img src="{{ asset('storage/'.$image->image) }}" alt="sponsorship" class="h-20 object-contain">
            @endforeach          
          @else              
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="sponsorship" class="h-20 object-contain">
          @endif

        </div>
      </div>
      <a href="https://wa.me/62{{ $setting->phone ?? 0 }}" target="_blank" class="mt-8 inline-block px-6 py-3 bg-amber-400 text-white font-semibold rounded-xl shadow hover:bg-amber-500">Ajukan Sponsorship</a>
    </div>
  </section>

  <!-- Live Chat -->
  <section id="chat" class="bg-sky-700 py-16 text-white text-center">
    <h3 class="text-3xl font-bold mb-6">Live Chat</h3>
    <p class="mb-4">Butuh bantuan atau informasi lebih lanjut? Hubungi kami melalui live chat di bawah ini.</p>
    <a href="https://wa.me/62{{ $setting->phone ?? 0 }}" target="_blank" class="px-6 py-3 bg-green-500 rounded-xl font-semibold hover:bg-green-600">Chat via WhatsApp</a>
  </section>

  <!-- Footer -->
  <footer class="bg-sky-900 text-white py-6 text-center">
    <p>&copy; 2025 Karimunjawa StrikeFest | www.karimunjawastrikefest.com</p>
  </footer>

  <script>
    // Countdown Timer
    const countdown = document.getElementById('countdown');
    const eventDate = new Date('Nov 14, 2025 00:00:00').getTime();
    setInterval(() => {
      const now = new Date().getTime();
      const distance = eventDate - now;
      if (distance < 0) {
        countdown.innerHTML = "Event Sedang Berlangsung!";
        return;
      }
      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);
      countdown.innerHTML = `${days}h ${hours}j ${minutes}m ${seconds}d`;
    }, 1000);
  </script>
</div>