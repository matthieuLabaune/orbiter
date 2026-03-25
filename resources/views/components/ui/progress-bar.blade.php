@props(['value' => 0, 'max' => 100, 'color' => 'emerald', 'showLabel' => true])

@php
$percentage = $max > 0 ? round(($value / $max) * 100) : 0;
$barColors = [
    'emerald' => 'bg-emerald-500',
    'blue' => 'bg-blue-500',
    'amber' => 'bg-amber-500',
    'red' => 'bg-red-500',
];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <div class="flex-1 bg-slate-700 rounded-full h-2 overflow-hidden">
        <div class="{{ $barColors[$color] ?? 'bg-emerald-500' }} h-full rounded-full transition-all duration-500"
             style="width: {{ $percentage }}%"></div>
    </div>
    @if($showLabel)
        <span class="text-xs text-slate-400 font-mono w-10 text-right">{{ $percentage }}%</span>
    @endif
</div>
