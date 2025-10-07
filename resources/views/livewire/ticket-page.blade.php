<div class="space-y-3 p-3">
    @forelse ($event as $evt)
    <a wire:navigate.hover href="/event/{{ $evt->slug }}" class="flex flex-nowrap cursor-pointer" >
        <div class="dark:text-white bg-white dark:bg-zinc-800 p-3 w-full block"  style="mask: radial-gradient(10px at 20px 20px, transparent 98%, black) -20px -20px;">
                <div class="font-bold">
                    {{ $evt->title }}
                </div>
                <div class="flex justify-between">
                    <span>{{ $evt->subtitle }} </span> 
                    @php
                        $userTim = $partisipan->where('user_id', Auth::user()->id)->where('participantable_id',$evt->id)->value('team');
                        $namaKlub = $userTim != null ? App\Models\User::find($userTim)->klub :'' ;
                    @endphp
                    <span class="font-bold text-end">{{ $namaKlub }}<span>
                </div>
                <div class="flex justify-between mt-1">
                    @if ($evt->payments->where('user_id',Auth::user()->id)->sum('nominal') >= $evt->price)
                    <x-badge text="LUNAS" color="blue" outline xs/>                        
                    @else                        
                    <x-badge text="BELUM LUNAS" color="red" outline xs/>
                    @endif
                    <span class="text-xs">Rp{{ Number::format($evt->payments->where('user_id',Auth::user()->id)->sum('nominal'), locale: 'de') }}</span>
                </div>
        </div>
        <div class="right-7 translate-y-[calc(18%)] text-center absolute text-white font-extrabold uppercase z-10">
            <div >
                {{ Carbon\Carbon::parse($evt->date_from)->translatedFormat('M') }}
            </div>
            <div class="text-3xl -mt-2">
                {{ Carbon\Carbon::parse($evt->date_from)->translatedFormat('d') }}
            </div>
        </div>
        <div class="w-20 h-auto" style="mask: radial-gradient(10px at 20px 20px, transparent 98%, black) -20px -20px;">
            {{-- <div class="bg-[url('http://strikefest.test/storage/events/1/20250905-004236/01K4J1SGMBCJ0ZZJN2CQ17KKHS.jpeg')]">  --}}
                <img class="bg-cover bg-center h-full " src="{{ isset($evt->cover) ? Str::replace('%2F', '/',url('storage', $evt->cover)) : '' }}" alt="{{ $evt->title }}"
                >
                </img>
            {{-- </div> --}}
        </div>
    </a>
    @empty
        <span class="flex justify-center">Kosong, anda belum mendaftar event.</span>
    @endforelse
    <div>{{ $event->links() }}</div>
</div>