@props(['score'])

@php
if (is_null($score)) {
    $badgeClass = 'badge badge-gray';
    $label = 'N/A';
} elseif ($score >= 60) {
    $badgeClass = 'badge badge-red';
    $label = 'Critique';
} elseif ($score >= 30) {
    $badgeClass = 'badge badge-orange';
    $label = 'Élevé';
} elseif ($score >= 15) {
    $badgeClass = 'badge badge-blue';
    $label = 'Modéré';
} else {
    $badgeClass = 'badge badge-green';
    $label = 'Faible';
}
@endphp

<span {{ $attributes->merge(['class' => "$badgeClass font-mono"]) }}>
    <span class="font-bold">{{ $score ?? '—' }}</span>
    <span class="text-[10px] opacity-75 ml-0.5">{{ $label }}</span>
</span>
