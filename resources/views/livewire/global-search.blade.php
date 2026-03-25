<div
    x-data="{ showSearch: false }"
    x-on:keydown.window.meta.k.prevent="showSearch = true; $nextTick(() => $refs.searchInput?.focus())"
    x-on:keydown.window.ctrl.k.prevent="showSearch = true; $nextTick(() => $refs.searchInput?.focus())"
    x-on:keydown.escape.window="showSearch = false; $wire.set('query', '')"
>
    {{-- Trigger button --}}
    <button @click="showSearch = true; $nextTick(() => $refs.searchInput?.focus())"
            class="flex items-center gap-2 px-3 py-1.5 text-sm rounded-lg transition-all cursor-pointer"
            style="color: var(--orbiter-text-muted); background: var(--orbiter-surface-2); border: 1px solid var(--orbiter-border);"
        <x-lucide-search class="w-4 h-4" />
        <span class="hidden sm:inline">Rechercher...</span>
        <kbd class="hidden sm:inline text-[10px] px-1.5 py-0.5 rounded" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-muted); border: 1px solid var(--orbiter-border);">
            ⌘K
        </kbd>
    </button>

    {{-- Search dialog --}}
    <template x-teleport="body">
        <div x-show="showSearch" x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-start justify-center pt-[15vh]"
             @click.self="showSearch = false; $wire.set('query', '')">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm"></div>

            {{-- Panel --}}
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700/50 rounded-xl shadow-2xl overflow-hidden"
                 @click.stop>
                {{-- Input --}}
                <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-200 dark:border-slate-700/50">
                    <x-lucide-search class="w-5 h-5 text-gray-400 dark:text-slate-500 shrink-0" />
                    <input type="text"
                           x-ref="searchInput"
                           wire:model.live.debounce.300ms="query"
                           placeholder="Rechercher REQ, TEST, tâche, ADR..."
                           class="flex-1 bg-transparent border-none text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-0 text-sm">
                    <kbd class="text-[10px] px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 rounded border border-gray-200 dark:border-slate-700">
                        ESC
                    </kbd>
                </div>

                {{-- Results --}}
                @if(strlen($query) >= 2)
                    <div class="max-h-80 overflow-y-auto">
                        @if($results->isEmpty())
                            <div class="px-4 py-8 text-center text-gray-400 dark:text-slate-500 text-sm">
                                Aucun résultat pour "{{ $query }}"
                            </div>
                        @else
                            <div class="py-2">
                                @foreach($results as $result)
                                    <a href="{{ $result['url'] }}"
                                       wire:click="selectResult"
                                       @click="showSearch = false"
                                       class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <x-dynamic-component :component="'lucide-' . $result['icon']"
                                            class="w-4 h-4 text-gray-400 dark:text-slate-500 shrink-0" />
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                @if($result['ref'])
                                                    <span class="font-mono text-xs text-blue-600 dark:text-blue-400">{{ $result['ref'] }}</span>
                                                @endif
                                                <span class="text-sm text-gray-800 dark:text-slate-200 truncate">{{ $result['title'] }}</span>
                                            </div>
                                            <span class="text-[10px] text-gray-400 dark:text-slate-600">{{ $result['project'] }}</span>
                                        </div>
                                        <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 rounded capitalize">
                                            {{ $result['type'] }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="px-4 py-6 text-center text-gray-400 dark:text-slate-500 text-xs">
                        Tapez au moins 2 caractères pour rechercher
                    </div>
                @endif
            </div>
        </div>
    </template>
</div>
