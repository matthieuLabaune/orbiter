<?php

namespace App\Livewire\Architecture;

use App\Models\Diagram;
use App\Models\DiagramVersion;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DiagramEditor extends Component
{
    public Project $project;
    public Diagram $diagram;
    public string $mermaidSource = '';
    public string $title = '';

    public function mount(): void
    {
        $this->mermaidSource = $this->diagram->mermaid_source;
        $this->title = $this->diagram->title;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'mermaidSource' => 'required|string',
        ]);

        // Snapshot before saving
        DiagramVersion::create([
            'diagram_id' => $this->diagram->id,
            'mermaid_source' => $this->diagram->mermaid_source,
            'version' => $this->diagram->version,
            'changed_by' => Auth::id(),
        ]);

        $this->diagram->update([
            'title' => $this->title,
            'mermaid_source' => $this->mermaidSource,
            'version' => $this->diagram->version + 1,
        ]);

        session()->flash('success', "Diagramme sauvegardé (v{$this->diagram->version}).");
    }

    public function render()
    {
        return view('livewire.architecture.diagram-editor');
    }
}
