<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Karimunjawa StrikeFest 2025</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .glass { background: rgba(255,255,255,0.3); backdrop-filter: blur(8px);} 
    .hero-bg { background-size: cover; background-position: center; }
    .sponsor-slider { display: flex; animation: scroll 20s linear infinite; }
    @keyframes scroll {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
  </style>
</head>
<body class="antialiased font-sans bg-gradient-to-b from-sky-100 to-blue-50 text-slate-800">
  <!-- NAV -->
  <header class="fixed top-0 left-0 right-0 z-40 bg-white/90 shadow">
    <nav class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('/assets/images/LogoStrikefest2025.png') }}" alt="Logo StrikeFest" class="w-12 h-12">
        <h1 class="font-bold text-lg text-sky-700">Karimunjawa StrikeFest 2025</h1>
      </div>
      <div class="hidden md:flex items-center gap-6 text-sm font-medium">
        <a href="#info" class="hover:text-sky-700">Informasi Event</a>
        <a href="#panduan" class="hover:text-sky-700">Panduan Lomba</a>
        <a href="#galeri" class="hover:text-sky-700">Galeri</a>
        <a href="#sponsor" class="hover:text-sky-700">Sponsor</a>
        <a href="https://wa.me/6281325171106" class="hover:text-sky-700">Live Chat</a>
        <a href="/login" class="font-bold px-3 py-1 rounded-xl bg-blue-300 hover:text-sky-700">LOGIN</a>
      </div>
    </nav>
  </header>

  <!-- HERO -->
  <section class="pt-20 relative">
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
  <section id="info" class="max-w-6xl mx-auto py-16 px-4">
    <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Informasi Event</h3>
    <p class="text-gray-700 leading-relaxed text-center max-w-3xl mx-auto">
      Karimunjawa StrikeFest merupakan inisiatif besar untuk mengembangkan sektor perikanan dan sport tourism di Jawa Tengah. Event ini memperkenalkan keindahan alam Karimunjawa, kompetisi memancing internasional, kegiatan konservasi laut (Ngopeni Laut), serta pameran UMKM lokal.
    </p>
  </section>

  <!-- Pendaftaran -->
  <section id="daftar" class="bg-white py-16">
    <div class="max-w-4xl mx-auto px-4">
      <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Daftar atau Login</h3>
      <div class="flex justify-center space-x-3">
          <a href="{{ url('/register') }}">
            <button class="px-6 py-3 bg-sky-700 text-white rounded-xl font-semibold hover:bg-sky-800">Kirim Pendaftaran</button>
          </a>
          <a href="{{ url('/login') }}">
            <button class="px-6 py-3 bg-sky-500 text-white rounded-xl font-semibold hover:bg-sky-800">Login & Cek Tiket</button>
          </a>
      </div>
    </div>
  </section>

  <!-- Panduan Lomba -->
  <section id="panduan" class="max-w-6xl mx-auto py-16 px-4">
    <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Panduan Lomba</h3>
    <ul class="list-disc list-inside space-y-3 text-gray-700 max-w-3xl mx-auto">
      <li>Lomba berlangsung di perairan laut Karimunjawa (ditentukan panitia).</li>
      <li>Setiap kapal maksimal 4 orang peserta.</li>
      <li>Peserta membawa perlengkapan pribadi (alat pancing, umpan, pelampung, perlindungan diri).</li>
      <li>Peserta wajib hadir tepat waktu dan mematuhi peraturan panitia.</li>
      <li>Total hadiah Rp. 150.000.000.</li>
    </ul>
  </section>

  <!-- Galeri -->
  <section id="galeri" class="bg-sky-50 py-16">
    <div class="max-w-6xl mx-auto px-4">
      <h3 class="text-3xl font-bold text-center mb-8 text-sky-700">Galeri Karimunjawa</h3>
      <div class="grid md:grid-cols-3 gap-6">
        <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=60" alt="Pantai Karimunjawa" class="rounded-xl shadow">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=60" alt="Laut Karimunjawa" class="rounded-xl shadow">
        <img src="https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=600&q=60" alt="Sunset Karimunjawa" class="rounded-xl shadow">
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
          <img src="{{ asset('/assets/images/PemrovJawaTengah.png') }}" alt="Pemprov Jawa Tengah" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/JatengNgopeniNglakoni.png') }}" alt="Ngopeni Nglakoni" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 1" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 2" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 3" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 4" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 5" class="h-20 object-contain">
          <!-- Duplicate logos for seamless loop -->
          <img src="{{ asset('/assets/images/PemrovJawaTengah.png') }}" alt="Pemprov Jawa Tengah" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/JatengNgopeniNglakoni.png') }}" alt="Ngopeni Nglakoni" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 1" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 2" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 3" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 4" class="h-20 object-contain">
          <img src="{{ asset('/assets/images/OpenSponsorship.png') }}" alt="Sponsor 5" class="h-20 object-contain">
        </div>
      </div>
      <a href="#" class="mt-8 inline-block px-6 py-3 bg-amber-400 text-white font-semibold rounded-xl shadow hover:bg-amber-500">Ajukan Sponsorship</a>
    </div>
  </section>

  <!-- Live Chat -->
  <section id="chat" class="bg-sky-700 py-16 text-white text-center">
    <h3 class="text-3xl font-bold mb-6">Live Chat</h3>
    <p class="mb-4">Butuh bantuan atau informasi lebih lanjut? Hubungi kami melalui live chat di bawah ini.</p>
    <a href="https://wa.me/6281325171106" target="_blank" class="px-6 py-3 bg-green-500 rounded-xl font-semibold hover:bg-green-600">Chat via WhatsApp</a>
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
</body>
</html>
