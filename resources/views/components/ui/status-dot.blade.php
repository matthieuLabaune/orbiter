@props(['status' => 'neutral'])

@php
$colors = [
    'go' => 'bg-emerald-500 shadow-emerald-500/50',
    'no_go' => 'bg-red-500 shadow-red-500/50',
    'warning' => 'bg-amber-500 shadow-amber-500/50',
    'neutral' => 'bg-slate-500 shadow-slate-500/50',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-block w-3 h-3 rounded-full shadow-sm ' . ($colors[$status] ?? $colors['neutral'])]) }}></span>
