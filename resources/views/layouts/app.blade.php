<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Orbiter') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=geist:400,500,600,700&family=instrument-serif:400,400i&family=jetbrains-mono:400,500&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            body { font-family: 'Geist', -apple-system, BlinkMacSystemFont, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
            h1, h2, h3, h4 { letter-spacing: -0.02em; }
            .font-display { font-family: 'Instrument Serif', Georgia, serif; }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex">
            @auth
                @include('layouts.sidebar')
            @endauth

            <div class="flex-1 flex flex-col min-h-screen">
                @auth
                    <div class="h-12 flex items-center justify-between px-6 sticky top-0 z-30"
                         style="background: color-mix(in srgb, var(--o-bg) 80%, transparent); backdrop-filter: blur(20px) saturate(1.5); border-bottom: 1px solid var(--o-border);">
                        <livewire:global-search />
                        <div class="flex items-center gap-4">
                            <button @click="darkMode = !darkMode"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center cursor-pointer transition-all hover:scale-105"
                                    style="color: var(--o-text-3); background: var(--o-surface-2);">
                                <x-lucide-sun class="w-3.5 h-3.5" x-show="darkMode" x-cloak />
                                <x-lucide-moon class="w-3.5 h-3.5" x-show="!darkMode" />
                            </button>
                            <a href="{{ route('profile.edit') }}" class="text-xs font-medium" style="color: var(--o-text-3);">{{ Auth::user()->name }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="cursor-pointer opacity-30 hover:opacity-80 transition-opacity" style="color: var(--o-text-3);">
                                    <x-lucide-log-out class="w-3.5 h-3.5" />
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @isset($header)
                    <header class="px-6 lg:px-8 py-5" style="border-bottom: 1px solid var(--o-border);">
                        <div class="max-w-7xl mx-auto">{{ $header }}</div>
                    </header>
                @endisset

                <main class="flex-1 px-6 lg:px-8 py-6">
                    <div class="max-w-7xl mx-auto">{{ $slot }}</div>
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
