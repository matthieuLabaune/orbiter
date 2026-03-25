<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && true) }"
      x-init="$watch('darkMode', val => { localStorage.setItem('theme', val ? 'dark' : 'light'); })"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Orbiter') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&family=instrument-serif:400,400i&family=jetbrains-mono:400,500,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            body { font-family: 'DM Sans', system-ui, -apple-system, sans-serif; }
            h1, h2, h3, h4 { letter-spacing: -0.025em; }
            .font-display { font-family: 'Instrument Serif', Georgia, serif; }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex">
            @auth
                @include('layouts.sidebar')
            @endauth

            <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
                {{-- Top bar — glass morphism --}}
                @auth
                    <div class="h-11 border-b flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30"
                         style="border-color: var(--orbiter-border); background: color-mix(in srgb, var(--orbiter-bg) 80%, transparent); backdrop-filter: blur(16px) saturate(1.4);">
                        <div class="flex items-center gap-3">
                            <livewire:global-search />
                        </div>
                        <div class="flex items-center gap-4">
                            <button @click="darkMode = !darkMode"
                                    class="p-1.5 rounded-md cursor-pointer transition-all duration-200 hover:scale-110"
                                    style="color: var(--orbiter-text-muted);">
                                <x-lucide-sun class="w-3.5 h-3.5" x-show="darkMode" />
                                <x-lucide-moon class="w-3.5 h-3.5" x-show="!darkMode" />
                            </button>
                            <a href="{{ route('profile.edit') }}" class="text-xs font-medium" style="color: var(--orbiter-text-muted);">
                                {{ Auth::user()->name }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="cursor-pointer opacity-40 hover:opacity-100 transition-opacity">
                                    <x-lucide-log-out class="w-3.5 h-3.5" style="color: var(--orbiter-text-muted);" />
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @isset($header)
                    <header class="border-b px-4 sm:px-6 lg:px-8 py-4" style="border-color: var(--orbiter-border);">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
