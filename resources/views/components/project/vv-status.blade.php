@props(['status'])

@php
$styles = [
    'validated' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
    'verified' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
    'in_test' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
    'untested' => 'bg-slate-500/20 text-slate-400 border-slate-500/30',
    'failed' => 'bg-red-500/20 text-red-400 border-red-500/30',
];
$labels = [
    'validated' => 'Validé',
    'verified' => 'Vérifié',
    'in_test' => 'En test',
    'untested' => 'Non testé',
    'failed' => 'Échoué',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-block px-2 py-0.5 rounded-full text-xs font-medium border ' . ($styles[$status] ?? '')]) }}>
    {{ $labels[$status] ?? $status }}
</span>
