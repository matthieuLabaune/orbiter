@props(['score'])

@php
if (is_null($score)) {
    $color = 'text-slate-500';
    $bg = 'bg-slate-500/10';
    $label = 'N/A';
} elseif ($score >= 60) {
    $color = 'text-red-400';
    $bg = 'bg-red-500/10';
    $label = 'Critique';
} elseif ($score >= 30) {
    $color = 'text-amber-400';
    $bg = 'bg-amber-500/10';
    $label = 'Élevé';
} elseif ($score >= 15) {
    $color = 'text-blue-400';
    $bg = 'bg-blue-500/10';
    $label = 'Modéré';
} else {
    $color = 'text-emerald-400';
    $bg = 'bg-emerald-500/10';
    $label = 'Faible';
}
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-mono $color $bg"]) }}>
    <span class="font-bold">{{ $score ?? '—' }}</span>
    <span class="text-[10px] opacity-75">{{ $label }}</span>
</span>
