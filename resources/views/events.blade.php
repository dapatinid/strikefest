<x-app-layout>
    <div class="p-3">
    @php
    $Event = App\Models\Event::orderby('date_from')->paginate(10);
    $Gallery = App\Models\Gallery::query()->where('is_active',true)->where('target','gallery')->get();
        // dd($Gallery);
    foreach ( $Gallery as $img) {
        $imgs[] = [
            'src' => Str::replace('%2F', '/',url('storage', $img->image)),
            'alt' => 'Gallery',
        ];
    }
    // dd($imgs);
    @endphp

    <div class="">
        @if (count($imgs) == 1)
            <x-carousel autoplay stop-on-hover without-indicators round wrapper="aspect-[3/1]"
            :images="$imgs"
            />
        @else
            <x-carousel autoplay stop-on-hover round wrapper="aspect-[3/1]"
            :images="$imgs"
            />
        @endif
    </div>

    <div class="mt-5 mb-2 text-xl dark:text-white">
        Join Us
    </div>

    <div class="space-y-3 mb-7 ">
    @foreach ($Event as $ev)
        <a wire:navigate.hover href="{{ route('event', ['slug' => $ev->slug]) }}" class="p-2 flex space-x-4 bg-white dark:bg-primary-700 rounded-xl">
            <div class="flex-none"><img src="{{ Str::replace('%2F', '/',url('storage', $ev->cover)) }}" alt="" class="size-18 object-cover aspect-square rounded-md"></div>
            <div class="w-auto shrink">
                <div class="font-bold dark:text-white">{{ $ev->title }}</div>
                <div class="text-xs dark:text-primary-200">{{ $ev->subtitle }} {{ $ev->categories }}</div>
                {{-- <div class="text-gray-500">@currency($ev->price)</div> --}}
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
            <div class="flex-none ms-auto bg-primary-600 rounded-r-md text-white w-5 items-center relative pt-6"><x-icon name="chevron-right" class="h-5 w-5"/></div>
        </a>
    @endforeach
    </div>
    <div>{{ $Event->links() }}</div>
    {{-- <x-card header="Welcome to the TallStackUI Starter Kit">
        <div class="space-y-2">
            <p>
                👋🏻 This is the TallStackUI starter kit for Laravel 12. With this TallStackUI starter kit you will be able to enjoy a ready-to-use application to initialize your next Laravel 12 project with TallStackUI.
            </p>
            <div class="mt-4 space-y-2">
                <i>
                    "What this starter kit includes out of the box?"
                </i>
                <ul class="ml-2 mt-2 list-inside list-decimal font-semibold">
                    <li>Laravel v12</li>
                    <li>Livewire v3</li>
                    <li>TallStackUI v2</li>
                    <li>TailwindCSS v4</li>
                </ul>
                <p>And also:</p>
                <ul class="ml-2 mt-2 list-inside list-decimal font-semibold">
                    <li><a href="https://github.com/barryvdh/laravel-debugbar" target="_blank">DebugBar</a></li>
                    <li><a href="https://github.com/larastan/larastan" target="_blank">LaraStan</a></li>
                    <li><a href="https://pestphp.com/" target="_blank">Pest</a></li>
                    <li><a href="https://laravel.com/docs/pint" target="_blank">Pint</a></li>
                </ul>
            </div>
        </div>
        <x-slot:footer>
            <span class="text-xs">
                ⚠️ <x-link href="https://tallstackui.com/docs/v2/starter-kit" bold blank sm>Make sure to read the docs about the starter kit!</x-link>
            </span>
        </x-slot:footer>
    </x-card> --}}
    </div>
</x-app-layout>
