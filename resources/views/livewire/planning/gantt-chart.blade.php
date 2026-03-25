<div>
    @if($ganttTasks->isEmpty())
        <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-8 text-center text-gray-400 dark:text-slate-500">
            Aucune tâche avec des dates pour afficher le Gantt.
        </div>
    @else
        <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-4 overflow-x-auto">
            <div id="gantt-container" wire:ignore></div>
        </div>

        <style>
            .gantt .bar-done .bar-progress { fill: #10b981; }
            .gantt .bar-progress .bar-progress { fill: #3b82f6; }
            .gantt .bar-blocked .bar-progress { fill: #ef4444; }
            .gantt .bar-todo .bar-progress { fill: #475569; }
            .gantt .bar-label { fill: #e2e8f0; font-size: 12px; }
            .gantt .grid-header { fill: #0f172a; stroke: #1e293b; }
            .gantt .grid-row { fill: transparent; }
            .gantt .grid-row:nth-child(even) { fill: rgba(15, 23, 42, 0.3); }
            .gantt .row-line { stroke: #1e293b; }
            .gantt .tick { stroke: #1e293b; }
            .gantt .today-highlight { fill: rgba(59, 130, 246, 0.1); }
            .gantt .lower-text, .gantt .upper-text { fill: #94a3b8; font-size: 11px; }
            .gantt .handle-group { display: none; }
            .gantt .arrow { stroke: #475569; fill: none; }
        </style>

        @script
        <script>
            import Gantt from 'frappe-gantt';

            const tasks = @json($ganttTasks);

            if (tasks.length > 0) {
                const gantt = new Gantt('#gantt-container', tasks, {
                    view_mode: 'Week',
                    date_format: 'YYYY-MM-DD',
                    popup_trigger: 'click',
                    custom_popup_html: function(task) {
                        return `<div class="bg-slate-800 text-white p-3 rounded-lg shadow-xl text-sm border border-slate-700">
                            <div class="font-medium">${task.name}</div>
                            <div class="text-slate-400 text-xs mt-1">Progression : ${task.progress}%</div>
                        </div>`;
                    },
                    language: 'fr',
                });
            }
        </script>
        @endscript
    @endif
</div>
