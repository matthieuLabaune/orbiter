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
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&family=jetbrains-mono:400,500,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            @auth
                @include('layouts.sidebar')
            @endauth

            <div class="flex-1 flex flex-col min-h-screen">
                {{-- Compact top bar (replaces Breeze navigation) --}}
                @auth
                    <div class="h-12 border-b border-gray-200 dark:border-slate-800/50 bg-white/80 dark:bg-[#0a0f1e]/80 backdrop-blur-sm flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
                        <div class="flex items-center gap-3">
                            <livewire:global-search />
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('profile.edit') }}" class="text-xs text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-300 transition-colors">
                                {{ Auth::user()->name }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs text-gray-400 dark:text-slate-600 hover:text-red-500 transition-colors cursor-pointer">
                                    <x-lucide-log-out class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @isset($header)
                    <header class="bg-white/80 border-b border-gray-200 dark:bg-slate-900/50 dark:border-slate-700/50">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
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
