import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Mermaid.js — diagrammes
import mermaid from 'mermaid';
mermaid.initialize({
    startOnLoad: true,
    theme: 'dark',
    themeVariables: {
        darkMode: true,
        background: '#0f172a',
        primaryColor: '#3b82f6',
        primaryTextColor: '#e2e8f0',
        lineColor: '#475569',
    },
});

// Re-render mermaid after Livewire navigation
document.addEventListener('livewire:navigated', () => {
    mermaid.run();
});
