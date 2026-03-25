@props(['headers' => []])

<div {{ $attributes->merge(['class' => 'overflow-x-auto rounded-lg border border-gray-200 dark:border-slate-700']) }}>
    <table class="w-full text-sm text-left">
        @if(count($headers))
            <thead class="text-xs text-gray-500 dark:text-slate-400 uppercase bg-gray-50 dark:bg-slate-800/50">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-4 py-3">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
