<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold" style="color: var(--o-text);">Deploy Readiness</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        {{-- Current status --}}
        <div class="surface p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold" style="color: var(--o-text);">Statut actuel</h3>
                @php $isGo = $currentReadiness['result'] === 'go'; @endphp
                <div class="flex items-center gap-2">
                    <x-ui.status-dot :status="$isGo ? 'go' : 'no_go'" />
                    <span class="text-lg font-bold font-mono {{ $isGo ? 'text-emerald-500' : 'text-red-500' }}">
                        {{ $isGo ? 'GO' : 'NO-GO' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($currentReadiness['modules'] as $name => $status)
                    @php $modGo = $status['status'] === 'go'; @endphp
                    <div class="flex items-center justify-between p-3 rounded-lg border {{ $modGo ? 'border-emerald-500/20 bg-emerald-500/5' : 'border-red-500/20 bg-red-500/5' }}">
                        <div>
                            <div class="text-sm font-medium {{ $modGo ? 'text-emerald-500' : 'text-red-500' }}">{{ $name }}</div>
                            <div class="text-xs" style="color: var(--o-text-4);">{{ $status['reason'] }}</div>
                        </div>
                        <span class="text-xs font-bold font-mono {{ $modGo ? 'text-emerald-500' : 'text-red-500' }}">
                            {{ $modGo ? 'GO' : 'NO-GO' }}
                        </span>
                    </div>
                @endforeach
            </div>

            @if(!empty($currentReadiness['blocking_items']))
                <div class="mt-4 p-3 bg-red-500/5 border border-red-500/20 rounded-lg">
                    <h4 class="text-sm font-medium text-red-400 mb-2">Blocking items</h4>
                    @foreach($currentReadiness['blocking_items'] as $item)
                        <div class="text-xs py-1" style="color: var(--o-text-4);">
                            <span class="font-mono text-red-500">{{ $item['ref'] }}</span> — {{ $item['reason'] }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- History --}}
        @if($reviews->isNotEmpty())
            <div class="surface p-6">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-4" style="color: var(--o-text-4);">Historique</h3>
                <div class="space-y-3">
                    @foreach($reviews as $review)
                        <div class="flex items-center justify-between py-2 border-b last:border-0" style="border-color: var(--o-border);">
                            <div>
                                <span class="font-mono text-sm" style="color: var(--o-text);">{{ $review->ref }}</span>
                                <span class="text-sm" style="color: var(--o-text-4);">→ {{ $review->target_version }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-ui.status-dot :status="$review->result === 'go' ? 'go' : 'no_go'" />
                                <span class="text-xs" style="color: var(--o-text-4);">{{ $review->decided_at?->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
