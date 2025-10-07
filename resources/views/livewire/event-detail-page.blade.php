<div class="space-y-5 pb-10 p-3">
    <x-card>
        @if (isset($event->cover) )                
        <a href="{{ Str::replace('%2F', '/',url('storage', $event->cover)) }}" target="_blank">
            <img class="w-full aspect-video object-cover rounded-md" src="{{ isset($event->cover) ? Str::replace('%2F', '/',url('storage', $event->cover)) : '' }}" alt="{{ $event->title }}">
        </a>
        @endif

        <div class="block text-center mt-5">
            <h1 class="text-2xl font-bold dark:text-white">{{ $event->title }}</h1>
            <h4>{{ $event->subtitle }}</h4>
        </div>

        <div class="p-3 pb-10 [&>ul]:list-disc [&>ul]:ml-5 dark:text-gray-200">
            <p class="max-w-md">
                @php
                    $paragraf = Str::replace('<blockquote>', '<blockquote class="relative border-s-4 border-green-500 dark:border-green-300 py-5 ps-4 sm:ps-6 bg-zinc-100 dark:bg-zinc-800 "><div class="relative z-10"><p class="text-gray-700 dark:text-white"><em>', $event->body);
                    $paragraf = Str::replace('</blockquote>', '</em></p></blockquote>', $paragraf);
                @endphp
                {!! Str::markdown(str($paragraf)->sanitizeHtml()) !!}
            </p>
        </div>

        <div class="w-full text-center border-t-5 border-b-5 border-primary-100 dark:border-primary-700 p-5">
            @if ($event->categories || $event->tags)
                @php
                    $categories = Str::of($event->categories)->explode(',');
                    $tags = Str::of($event->tags)->explode(',');
                @endphp
                <p class="text-gray-700 dark:text-gray-400">
                    @foreach ($categories as $category)
                        <span class="inline-block px-2 m-1 bg-gray-200 rounded-md dark:text-zinc-700">
                            {{ $category }}
                        </span>
                    @endforeach
                    @foreach ($tags as $tag)
                        <span class="inline-block px-2 m-1 bg-gray-200 rounded-md dark:text-zinc-700">
                            {{ $tag }}
                        </span>
                    @endforeach
                </p>
            @endif
            <div>
                Acara Mulai {{ $event->date_from }} hingga {{ $event->date_until }}
            </div>
        </div> 
    </x-card>

    <hr class="border-2">

    @auth

    @if ($partisipan->where('user_id', Auth::user()->id)->where('participantable_id',$event->id)->count() > 0 && $event->participants->value('team') != null )        
    <x-alert color="neutral" class="block">
        @php
            $namaKlub = ($event->participants->value('team')) != null ? App\Models\User::find($event->participants->value('team'))->klub :'' ;
        @endphp
        <div class="font-bold">{{ "My Team / Club : " . $namaKlub }}</div>
        @foreach ($partisipan->where('participantable_id',$event->id)->where('team',$event->participants->value('team')) as $team_list)
            <div>{{ App\Models\User::find($team_list->user_id)->name }}</div>
        @endforeach
    </x-alert>
    

    <form id="ubah-team-{{ $event->id }}" wire:submit="ubah_team" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-card class="space-y-3">
            <x-slot:header>
                <div class="p-4 font-bold">
                    Ganti Tim <br>
                    <span class="text-xs font-medium">Sesuaikan nama tim mu. (Khusus kategori kelompok).</span>
                </div>
            </x-slot:header>

                <div>
                    <x-select.styled label="Team" wire:model="team" :options="$daftar_klub" searchable/>
                </div>
                
        </x-card>
    </form>
        <div class="mt-5">
            <x-button type="submit" form="ubah-team-{{ $event->id  }}" class="w-full md:w-auto">
                @lang('Ganti Tim')
            </x-button>
        </div>

    @endif

    <hr class="border-2">
    
    @if ($event->payments->where('user_id',Auth::user()->id)->sum('nominal') >= $event->price)      
        <x-alert color="indigo" icon="light-bulb">
            @lang('Lunas')
        </x-alert>
    @elseif ($event->participants->where('user_id',Auth::user()->id)->count() > 0)
        <x-alert color="amber" icon="light-bulb">
            @lang('Segera lakukan pelunasan')
        </x-alert>

        <div class="dark:text-white">Riwayat Pembayaran</div>
        @php
            $listPembayaran = $event->payments->where('user_id',Auth::user()->id);
        @endphp
        <table class="w-full border-collapse table-auto dark:text-white">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-800">
            <th class="px-4 py-2 border-b border-gray-300">Tanggal</th>
            <th class="px-4 py-2 border-b border-gray-300">Catatan</th>
            <th class="px-4 py-2 border-b border-gray-300">Bukti Transfer</th>
            <th class="px-4 py-2 border-b border-gray-300">via</th>
            <th class="px-4 py-2 border-b border-gray-300">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listPembayaran as $py)                    
            <tr>
            <td class="px-4 h-20 border-b border-gray-300 text-center">{{ Carbon\Carbon::parse($py->date_payment)->translatedFormat('Y-M-d H:i') }}</td>
            <td class="px-4 h-20 border-b border-gray-300">{{ $py->notes }}</td>
            <td class="px-4 h-20 border-b border-gray-300 flex justify-center items-center"><img src="{{ isset($py->image) ? Str::replace('%2F', '/',url('storage', $py->image)) : Str::replace('%2F', '/', url('/assets/images/stempel-kosong.png')) }}" alt="..." class=" size-10"></td>
            <td class="px-4 h-20 border-b border-gray-300">{{ $py->payment_method }}</td>
            <td class="px-4 h-20 border-b border-gray-300 text-end">Rp{{ Number::format($py->nominal, locale: 'de') }}</td>
            </tr>
            @endforeach
        </tbody>
        </table>
       
        <form id="bayar-event-{{ $event->id }}" wire:submit="bayar_event" class="grid grid-cols-1 gap-4">
        <x-card class="space-y-3">
            <x-slot:header>
                <div class="p-4 font-bold">
                    Pembayaran
                </div>
            </x-slot:header>
                <div class="justify-center gap-2 font-bold flex flex-wrap">
                    <span>Pembayaran ke</span>
                    <span>CV. CAHYA SEMERU</span>
                    <span>BRI 008301004142301</span>
                </div>
                <div>
                        @if ($image) 
                        {{-- <span>{{ $image->getFilename() }}</span> --}}
                            <img src="/storage/livewire-tmp/{{ $image->getFilename() }}" class="size-10">
                        @endif
                </div>
                <div>
                    <x-upload label="Upload Bukti Transfer" wire:model="image" delete close-after-upload/>
                </div>
                <div>
                    <x-select.styled label="Metode Bayar" wire:model="metode_bayar" required :options="[
                        ['label' => 'Transfer', 'value' => 'transfer'],
                        ['label' => 'Cash', 'value' => 'cash'],
                    ]" />
                </div>
                <div>
                    <x-input label="Nominal Bayar" wire:model="nominal_bayar" required x-mask:dynamic="$money($input, ',')" class="text-end"/>
                </div>
                <div>
                    <x-input label="Catatan" wire:model="catatan" hint="Sertakan pesan bila perlu."/>
                </div>
            
        </x-card>
        </form>
        <div class="flex justify-end mt-5">
            <x-button type="submit" form="bayar-event-{{ $event->id  }}" class="w-full md:w-auto">
                @lang('Bayar Sekarang')
            </x-button>
        </div>

    @endif

    @if ($event->participants->where('user_id',Auth::user()->id)->count() == 0)      
    
    <div class="text-center font-bold text-xl mt-6 dark:text-white">
        FORMULIR PENDAFTARAN
    </div>
    <form id="daftar-event-{{ $event->id }}" wire:submit="daftar_event" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-card class="space-y-3">
          <x-slot:header>
            <div class="p-4 font-bold">
                Nama Tim <br>
                <span class="text-xs font-medium">Abaikan kolom ini jika pendaftar perorangan.</span>
            </div>
          </x-slot:header>

            <div>
                <x-select.styled label="Team" wire:model="team" :options="$daftar_klub" searchable/>
            </div>
        
    </x-card>
    <x-card class="space-y-3">
          <x-slot:header>
            <div class="p-4 font-bold">
                Informasi Darurat <br>
                <span class="text-xs font-medium">Untuk penanganan darurat oleh panitia.</span>
            </div>
          </x-slot:header>

            <div>
                <x-input label="Nama Kontak Darurat" wire:model="name_emergency" required />
            </div>
            <div>
                <x-input label="Hubungan dengan Peserta" wire:model="relation_emergency" required hint="istri / kakak / adik / orang tua / teman / yang lain"/>
            </div>
            <div>
                <x-input label="Nomor Kontak Darurat" wire:model="phone_emergency" required placeholder="Usahakan No. Whatsapp" />
            </div>
        
    </x-card>
    <x-card class="space-y-3">
          <x-slot:header>
            <div class="p-4 font-bold">
                Verifikasi Kesehatan <br>
                <span class="text-xs font-medium">Silakan beri tanda ✔ sesuai kondisi.</span>
            </div>
          </x-slot:header>

            <x-checkbox wire:model="sehat_jasmani_rohani">
                <x-slot:label start>
                    <span class="font-bold">Jasmani Rohani</span><br> Saya dalam kondisi sehat jasmani dan rohani.
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="sehat_jantung">
                <x-slot:label start>
                    <span class="font-bold">Jantung</span><br> Saya tidak memiliki riwayat penyakit jantung.
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="tanpa_epilepsi">
                <x-slot:label start>
                    <span class="font-bold">Kejang</span><br> Saya tidak memiliki riwayat epilepsi / kejang.
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="sehat_pernafasan">
                <x-slot:label start>
                    <span class="font-bold">Pernafasan</span><br> Saya tidak memiliki riwayat asma kronis / gangguan pernapasan berat.
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="tidak_dalam_perawatan">
                <x-slot:label start>
                    <span class="font-bold">Tidak Dirawat</span><br> Saya tidak sedang dalam perawatan medis yang dapat membahayakan saat mengikuti kegiatan laut (misal: operasi baru, terapi intensif, dll).
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="mampu_berenang">
                <x-slot:label start>
                    <span class="font-bold">Renang</span><br> Saya mampu berenang / memiliki keterampilan dasar keselamatan di air.
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="surat_sehat">
                <x-slot:label start>
                    <span class="font-bold">Surat Sehat</span><br> Saya bersedia menunjukkan surat keterangan sehat dari dokter bila diminta panitia.
                </x-slot:label>
            </x-checkbox>
        
    </x-card>
    <x-card class="space-y-3">
          <x-slot:header>
            <div class="p-4 font-bold">
                Pernyataan & Persetujuan <br>
                <span class="text-xs font-medium">Dengan ini saya menyatakan bahwa :</span>
            </div>
          </x-slot:header>

            <x-checkbox wire:model="info_dipertanggungjawabkan">
                <x-slot:label start>
                    <span class="font-bold">Dipertanggungjawabkan</span><br> Seluruh data diri dan informasi kesehatan yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="sehat_dan_siap">
                <x-slot:label start>
                    <span class="font-bold">Sehat dan Siap</span><br> Saya dalam kondisi sehat dan siap mengikuti Karimunjawa StrikeFest 2025.
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="bebas_tuntutan">
                <x-slot:label start>
                    <span class="font-bold">Panitia Bebas Tuntutan</span><br> Saya bertanggung jawab penuh atas kondisi kesehatan pribadi saya, serta membebaskan panitia dari tuntutan hukum/medis bila terjadi hal-hal di luar kendali panitia.
                </x-slot:label>
            </x-checkbox>
    </x-card>
    <x-card class="space-y-3">
          <x-slot:header>
            <div class="p-4 font-bold">
                Pembayaran <br>
                <span class="text-xs font-medium">Abaikan jika pembayaran dilakukan nanti.</span>
            </div>
          </x-slot:header>
            <div class="justify-center gap-2 font-bold flex flex-wrap">
                <span>Pembayaran ke</span>
                <span>CV. CAHYA SEMERU</span>
                <span>BRI 008301004142301</span>
            </div>
            <div>
                    @if ($image) 
                    {{-- <span>{{ $image->getFilename() }}</span> --}}
                        <img src="/storage/livewire-tmp/{{ $image->getFilename() }}" class="size-10">
                    @endif
            </div>
            <div>
                <x-upload label="Upload Bukti Transfer" wire:model="image" delete close-after-upload/>
            </div>
            <div>
                <x-select.styled label="Metode Bayar" wire:model="metode_bayar" required :options="[
                    ['label' => 'Transfer', 'value' => 'transfer'],
                    ['label' => 'Cash', 'value' => 'cash'],
                ]" />
            </div>
            <div>
                <x-input label="Nominal Bayar" wire:model="nominal_bayar" required x-mask:dynamic="$money($input, ',')" class="text-end"/>
            </div>
            <div>
                <x-input label="Catatan" wire:model="catatan" hint="Sertakan pesan bila perlu."/>
            </div>
        
    </x-card>
    </form>
    <div class="flex justify-end mt-5">
        <x-button type="submit" form="daftar-event-{{ $event->id  }}" class="w-full md:w-auto">
            @lang('Ajukan')
        </x-button>
    </div>
    @endif
    @endauth
    @guest
    <div class="flex justify-end mt-5">
        <x-button class="w-full md:w-auto">
            <a href="/login">
                @lang('Ikut? Login dulu...')
            </a>
        </x-button>
    </div>
    @endguest



</div>
