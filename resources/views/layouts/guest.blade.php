<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Orbiter') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=geist:400,500,600,700&family=instrument-serif:400&family=jetbrains-mono:400,500&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Geist', -apple-system, BlinkMacSystemFont, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        </style>
    </head>
    <body class="antialiased" style="background: var(--o-bg);">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <a href="/" class="flex items-center gap-3">
                    <x-application-logo class="w-10 h-10" style="color: var(--o-accent);" />
                    <span class="text-2xl font-bold tracking-tight" style="color: var(--o-text);">Orbiter</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-6 surface-elevated">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs" style="color: var(--o-text-4);">
                Open source — MIT License
            </p>
        </div>
    </body>
</html>
