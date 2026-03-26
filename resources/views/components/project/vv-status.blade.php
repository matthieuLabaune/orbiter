@props(['status'])

@php
$classes = [
    'validated' => 'badge badge-green',
    'verified' => 'badge badge-blue',
    'in_test' => 'badge badge-orange',
    'untested' => 'badge badge-gray',
    'failed' => 'badge badge-red',
];
$labels = [
    'validated' => 'Validé',
    'verified' => 'Vérifié',
    'in_test' => 'En test',
    'untested' => 'Non testé',
    'failed' => 'Échoué',
];
@endphp

<span {{ $attributes->merge(['class' => $classes[$status] ?? 'badge badge-gray']) }}>
    {{ $labels[$status] ?? $status }}
</span>
