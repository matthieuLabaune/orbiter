<div class="space-y-4">
    @if(session('success'))
        <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Editor --}}
        <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400">Source Mermaid</h3>
                <span class="text-xs text-gray-300 dark:text-slate-600 font-mono">v{{ $diagram->version }}</span>
            </div>
            <input type="text" wire:model="title"
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white text-sm mb-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                   placeholder="Titre du diagramme">
            <textarea wire:model.live.debounce.500ms="mermaidSource" rows="20"
                      class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white font-mono text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"
                      spellcheck="false"></textarea>
            <div class="flex items-center justify-between mt-3">
                <a href="https://mermaid.js.org/intro/" target="_blank" rel="noopener" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                    Documentation Mermaid
                </a>
                <button wire:click="save"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                    Sauvegarder
                </button>
            </div>
        </div>

        {{-- Preview --}}
        <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-3">Prévisualisation</h3>
            <div class="bg-gray-50 dark:bg-slate-800/50 rounded-lg p-4 min-h-[400px] overflow-auto" wire:ignore>
                <pre class="mermaid" id="mermaid-preview">{{ $mermaidSource }}</pre>
            </div>
        </div>
    </div>

    @script
    <script>
        // Re-render mermaid preview on source change
        $wire.$watch('mermaidSource', async (value) => {
            const container = document.getElementById('mermaid-preview');
            if (container) {
                container.textContent = value;
                container.removeAttribute('data-processed');
                const { default: mermaid } = await import('mermaid');
                await mermaid.run({ nodes: [container] });
            }
        });
    </script>
    @endscript
</div>
