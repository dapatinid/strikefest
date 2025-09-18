<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="tallstackui_darkTheme()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- <title>{{ config('app.name', 'Acara') }}</title> --}}
        <title>{{ $title ?? 'StrikeFest' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <tallstackui:script />
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
            <style>
                div .shadow-sm {
                    box-shadow: 0px 0px;
                    /* border-radius: 1rem; */
                    /* border-radius: calc(infinity * 1px); */
                    }
            </style>
    </head>
    <body class="font-sans antialiased"
          x-cloak
          x-data="{ name: @js(auth()->user()->name ?? 'belum login') }"
          x-on:name-updated.window="name = $event.detail.name"
          x-bind:class="{ 'dark bg-dark-950': darkTheme, 'bg-zinc-50': !darkTheme }">
    <x-layout>
        <x-slot:top>
            <x-dialog />
            <x-toast />
        </x-slot:top>
        <x-slot:header>
            <x-layout.header without-mobile-button>
                <x-slot:left>
                    <!-- Opening -->
                    @if (request()->is('/'))
                        <button x-on:click="$dispatch('tallstackui-menu-mobile', { status : true })" class="md:hidden flex cursor-pointer">
                            <x-icon name="bars-3" class="size-6 text-primary-600"/>
                        </button>
                        <button x-on:click="$store['tsui.side-bar'].toggle()" class="md:flex hidden cursor-pointer">
                            <x-icon name="bars-3" class="size-6 text-primary-600"/>
                        </button>
                    @else
                        <button x-on:click="history.back()" class="md:hidden flex cursor-pointer">
                            <x-icon name="arrow-left" class="size-6 text-primary-600"/>
                        </button>
                        <button x-on:click="$store['tsui.side-bar'].toggle()" class="md:flex hidden cursor-pointer">
                            <x-icon name="bars-3" class="size-6 text-primary-600"/>
                        </button>
                    @endif
                </x-slot:left>
                <x-slot:middle>
                    <span class="text-base font-semibold text-black dark:text-white md:flex hidden cursor-pointer">{{ $title ?? 'StrikeFest' }}</span>
                    <button x-on:click="$dispatch('tallstackui-menu-mobile', { status : true })" class="md:hidden flex cursor-pointer">
                        <span class="text-base font-semibold text-black dark:text-white cursor-pointer">{{ $title ?? 'StrikeFest' }}</span>
                    </button>
                </x-slot:middle>
                <x-slot:right>
                    <x-dropdown>
                        <x-slot:action>
                            <div>
                                @guest    
                                <x-button href="/login" 
                                {{-- target="_blank" --}} sm
                                ><x-icon name="arrow-right-end-on-rectangle" class="w-5 text-white"/>
                                </x-button>                                
                                @endguest
                                @auth                                    
                                <button class="flex cursor-pointer items-center" x-on:click="show = !show">
                                    {{-- <span class="text-base font-semibold text-green-500" x-text="name"></span> --}}
                                    @if (Auth::check() && Auth::user()->image != null)
                                        <img src="{{ url('storage/'.Auth::user()->image) }}" alt="avatar" class="object-cover text-center mx-auto size-7 rounded-full">
                                    @else
                                        <img src="{{ url('storage/avatar/user.png') }}" alt="avatar" class="object-cover text-center mx-auto size-7 rounded-full">
                                    @endif
                                </button>

                                @endauth
                            </div>
                        </x-slot:action>
                        <div class="pt-3 pb-3">
                            @if (Auth::check() && Auth::user()->image != null)
                                <img src="{{ url('storage/'.Auth::user()->image) }}" alt="avatar" class="object-cover text-center mx-auto size-[80px] rounded-full">
                            @else
                                <img src="{{ url('storage/avatar/user.png') }}" alt="avatar" class="object-cover text-center mx-auto size-[80px] rounded-full">
                            @endif
                        </div>
                        <div class="text-secondary-600 dark:text-dark-300 flex justify-around pb-3">
                            {{ Auth::check() ? Auth::user()->name : 'Silakan Login' }}
                        </div>
                        <div class="flex justify-around pt-3 pb-3 border-b border-slate-100 dark:border-slate-600">
                            <x-theme-switch />
                            {{-- TOMBOL FULLSCREEN START --}}
                            <svg onclick="toggle_full_screen()" id="layarpenuh" class="cursor-pointer hover:text-yellow-500 text-zinc-500 dark:text-zinc-200" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                            </svg> 
                            <svg onclick="toggle_full_screen()" id="layarpenuhtutup" class="hidden cursor-pointer hover:text-yellow-500 text-zinc-500 dark:text-zinc-200" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9 3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5 5.25 5.25" />
                            </svg> 

                            <script language="JavaScript">
                                function toggle_full_screen()
                                {
                                    if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen))
                                    {
                                        if (document.documentElement.requestFullScreen){
                                            document.documentElement.requestFullScreen();
                                            document.getElementById("layarpenuh").classList.add("hidden");
                                            document.getElementById("layarpenuhtutup").classList.remove("hidden");
                                        }
                                        else if (document.documentElement.mozRequestFullScreen){ /* Firefox */
                                            document.documentElement.mozRequestFullScreen();
                                            document.getElementById("layarpenuh").classList.add("hidden");
                                            document.getElementById("layarpenuhtutup").classList.remove("hidden");
                                        }
                                        else if (document.documentElement.webkitRequestFullScreen){   /* Chrome, Safari & Opera */
                                            document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                                            document.getElementById("layarpenuh").classList.add("hidden");
                                            document.getElementById("layarpenuhtutup").classList.remove("hidden");
                                        }
                                        else if (document.msRequestFullscreen){ /* IE/Edge */
                                            document.documentElement.msRequestFullscreen();
                                            document.getElementById("layarpenuh").classList.add("hidden");
                                            document.getElementById("layarpenuhtutup").classList.remove("hidden");
                                        }
                                    }
                                    else
                                    {
                                        if (document.cancelFullScreen){
                                            document.cancelFullScreen();
                                            document.getElementById("layarpenuh").classList.remove("hidden");
                                            document.getElementById("layarpenuhtutup").classList.add("hidden");
                                        }
                                        else if (document.mozCancelFullScreen){ /* Firefox */
                                            document.mozCancelFullScreen();
                                            document.getElementById("layarpenuh").classList.remove("hidden");
                                            document.getElementById("layarpenuhtutup").classList.add("hidden");
                                        }
                                        else if (document.webkitCancelFullScreen){   /* Chrome, Safari and Opera */
                                            document.webkitCancelFullScreen();
                                            document.getElementById("layarpenuh").classList.remove("hidden");
                                            document.getElementById("layarpenuhtutup").classList.add("hidden");
                                        }
                                        else if (document.msExitFullscreen){ /* IE/Edge */
                                            document.msExitFullscreen();
                                            document.getElementById("layarpenuh").classList.remove("hidden");
                                            document.getElementById("layarpenuhtutup").classList.add("hidden");
                                        }
                                    }
                                }
                            </script>

                            {{-- TOMBOL FULLSCREEN END --}}
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown.items :text="__('Profile')" :href="route('user.profile')" wire:navigate.hover class="border-b border-slate-100 dark:border-slate-600"/>
                            @if (Auth::check() && Auth::user()->is_admin == true)
                            <x-dropdown.items :text="__('Control Panel')" href="/cpanel" />
                            @endif
                            <x-dropdown.items :text="__('Logout')" onclick="event.preventDefault(); this.closest('form').submit();" separator />
                        </form>
                    </x-dropdown>
                </x-slot:right>
            </x-layout.header>
        </x-slot:header>
        <x-slot:menu>
            <x-side-bar smart collapsible navigate-hover >
                <x-slot:brand>
                    <div class="mt-5 flex items-center justify-center">
                        {{-- <img src="{{ asset('/assets/images/tsui.png') }}" width="40" height="40" /> --}}
                        LOGO
                    </div>
                </x-slot:brand>
                <x-side-bar.item text="Home" icon="home" :route="route('home')" />

                @auth       
                <x-side-bar.item icon="ticket" text="Ticket"   :route="route('ticket')" />
                @if (auth()->user()->is_admin == true)
                    
                <x-side-bar.item icon="users" text="Users" badge="{{ App\Models\User::query()->whereNotIn('id', [Auth::id()])->count() }}"  :route="route('users.index')" />
                    
                @endif
                @endauth

                <x-side-bar.item text="Tentang" icon="information-circle" :route="route('about')" />
            </x-side-bar>
        </x-slot:menu>
        {{ $slot }}
        {{-- <footer class="w-full sticky bottom-2 grid grid-cols-5 md:hidden mt-5">
            <x-icon name="play" class="w-10 rotate-180 text-primary-600" onclick="history.back()"/>
        </footer> --}}
    </x-layout>

    @livewireScripts
    </body>
</html>
