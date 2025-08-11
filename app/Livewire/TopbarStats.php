<?php

namespace App\Livewire;

use Livewire\Component;

class TopbarStats extends Component
{
    public int $eventsTomorrow = 0;
    public ?int $currentBudget = null;
    public string $tzAlt = 'America/Argentina/Buenos_Aires';

    public function mount(): void
    {
        $this->tzAlt = request('tz', $this->tzAlt);
        $this->currentBudget  = $this->detectBudgetId();
        $this->eventsTomorrow = $this->countEventsTomorrow();
    }

    public function refreshStats(): void
    {
        $this->currentBudget  = $this->detectBudgetId();
        $this->eventsTomorrow = $this->countEventsTomorrow();
    }

    protected function detectBudgetId(): ?int
    {
        $record = request()->route('record') ?? request('budget') ?? null;
        if (is_numeric($record)) return (int) $record;
        if (is_string($record) && preg_match('/(\d+)/', $record, $m)) return (int) $m[1];
        return null;
    }

    protected function countEventsTomorrow(): int
    {
        // TODO: cambia por tu modelo/columna reales
        $tz = 'Europe/Madrid';
        $date = now($tz)->addDay()->toDateString();

        // return \App\Models\Event::whereDate('start_at', $date)->count();
        return 0; // temporal
    }

    public function render()
    {
        return view('livewire.topbar-stats');
    }
}

