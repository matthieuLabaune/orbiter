@props(['color' => 'gray'])

@php
$classes = [
    'green' => 'badge badge-green',
    'emerald' => 'badge badge-green',
    'blue' => 'badge badge-blue',
    'red' => 'badge badge-red',
    'amber' => 'badge badge-orange',
    'orange' => 'badge badge-orange',
    'purple' => 'badge badge-purple',
    'gray' => 'badge badge-gray',
    'slate' => 'badge badge-gray',
];
@endphp

<span {{ $attributes->merge(['class' => $classes[$color] ?? 'badge badge-gray']) }}>
    {{ $slot }}
</span>
