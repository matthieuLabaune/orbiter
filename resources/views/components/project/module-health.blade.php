@props(['health'])

@php
$total = $health['total'] ?? 0;
$items = [
    ['label' => 'Formalisées', 'value' => $health['formalized'] ?? 0, 'color' => 'slate'],
    ['label' => 'Couvertes', 'value' => $health['covered'] ?? 0, 'color' => 'blue'],
    ['label' => 'Vérifiées', 'value' => $health['verified'] ?? 0, 'color' => 'amber'],
    ['label' => 'Validées', 'value' => $health['validated'] ?? 0, 'color' => 'emerald'],
];
@endphp

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @foreach($items as $item)
        <div class="flex items-center justify-between text-xs">
            <span class="text-gray-500 dark:text-slate-400">{{ $item['label'] }}</span>
            <span class="text-gray-700 dark:text-slate-300 font-mono">{{ $item['value'] }}/{{ $total }}</span>
        </div>
        <x-ui.progress-bar :value="$item['value']" :max="$total" :color="$item['color']" :showLabel="false" />
    @endforeach
</div>
