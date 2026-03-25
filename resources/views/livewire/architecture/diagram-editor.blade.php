<div class="space-y-4">
    @if(session('success'))
        <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Editor --}}
        <div class="surface p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium" style="color: var(--orbiter-text-muted);">Source Mermaid</h3>
                <span class="text-xs font-mono" style="color: var(--orbiter-text-muted);">v{{ $diagram->version }}</span>
            </div>
            <input type="text" wire:model="title"
                   class="input-field mb-3"
                   placeholder="Titre du diagramme">
            <textarea wire:model.live.debounce.500ms="mermaidSource" rows="20"
                      class="input-field font-mono resize-none"
                      spellcheck="false"></textarea>
            <div class="flex items-center justify-between mt-3">
                <a href="https://mermaid.js.org/intro/" target="_blank" rel="noopener" class="text-xs" style="color: var(--orbiter-accent);">
                    Documentation Mermaid
                </a>
                <button wire:click="save"
                        class="btn-primary cursor-pointer">
                    Sauvegarder
                </button>
            </div>
        </div>

        {{-- Preview --}}
        <div class="surface p-4">
            <h3 class="text-sm font-medium mb-3" style="color: var(--orbiter-text-muted);">Prévisualisation</h3>
            <div class="rounded-lg p-4 min-h-[400px] overflow-auto" style="background: var(--orbiter-surface-2);" wire:ignore>
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
