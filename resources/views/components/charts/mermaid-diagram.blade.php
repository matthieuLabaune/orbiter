@props(['source'])

<div {{ $attributes->merge(['class' => 'mermaid-container bg-gray-100 dark:bg-slate-800/50 rounded-lg p-4 overflow-x-auto']) }}>
    <pre class="mermaid">{{ $source }}</pre>
</div>
