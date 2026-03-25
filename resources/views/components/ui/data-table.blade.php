@props(['headers' => []])

<div {{ $attributes->merge(['class' => 'overflow-x-auto rounded-lg']) }} style="border: 1px solid var(--orbiter-border);">
    <table class="w-full text-sm text-left">
        @if(count($headers))
            <thead class="text-xs uppercase" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-muted);">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-4 py-3">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y" style="--tw-divide-opacity: 1; border-color: var(--orbiter-border);">
            {{ $slot }}
        </tbody>
    </table>
</div>
