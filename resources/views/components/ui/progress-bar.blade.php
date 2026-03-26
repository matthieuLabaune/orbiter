@props(['value' => 0, 'max' => 100, 'color' => 'green', 'showLabel' => true])

@php
$percentage = $max > 0 ? round(($value / $max) * 100) : 0;
$varMap = [
    'green' => '--o-green', 'emerald' => '--o-green',
    'blue' => '--o-accent',
    'amber' => '--o-orange', 'orange' => '--o-orange',
    'red' => '--o-red',
    'slate' => '--o-text-4',
];
$colorVar = $varMap[$color] ?? '--o-accent';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background: var(--o-border);">
        <div class="h-full rounded-full transition-all duration-500"
             style="width: {{ $percentage }}%; background: var({{ $colorVar }});"></div>
    </div>
    @if($showLabel)
        <span class="text-[11px] font-mono w-10 text-right" style="color: var(--o-text-3);">{{ $percentage }}%</span>
    @endif
</div>
