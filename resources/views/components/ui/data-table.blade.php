@props(['headers' => []])

<div {{ $attributes->merge(['class' => 'overflow-x-auto rounded-lg border border-slate-700']) }}>
    <table class="w-full text-sm text-left">
        @if(count($headers))
            <thead class="text-xs text-slate-400 uppercase bg-slate-800/50">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-4 py-3">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-slate-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
