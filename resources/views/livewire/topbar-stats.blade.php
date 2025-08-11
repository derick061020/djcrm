<div
    class="flex items-center gap-3 dj-topbar-pills"
    wire:poll.30s="refreshStats"
    x-data="topbarClocks()"
    x-init="init()"
>
    <!-- Píldora: Presupuesto -->
    <div class="dj-pill">
        <span class="font-medium mr-2">Viendo Presupuestos:</span>
        <span class="font-semibold text-primary-600 dark:text-primary-400">
            {{ $currentBudget ?? '—' }}
        </span>
    </div>

    <!-- Píldora: Eventos mañana -->
    <div class="dj-pill">
        <span class="font-medium mr-2">Eventos Mañana:</span>
        <span class="font-semibold text-primary-600 dark:text-primary-400">
            {{ $eventsTomorrow }}
        </span>
    </div>

    <!-- Reloj España -->
    <div class="dj-pill">
        <span class="font-medium mr-2">España:</span>
        <span class="tabular-nums" x-text="esTime"></span>
    </div>

    <!-- Reloj alterno con preferencia -->
    <div class="dj-pill">
        <select class="bg-transparent border-0 focus:ring-0 text-sm font-medium pr-1" x-model="altTz" @change="saveTz()">
            <option value="America/Argentina/Buenos_Aires">Argentina</option>
            <option value="America/Caracas">Venezuela</option>
        </select>
        <span class="tabular-nums" x-text="altTime"></span>
    </div>
</div>

<script>
function topbarClocks() {
    return {
        esTime: '',
        altTime: '',
        altTz: localStorage.getItem('djpanel.tz') || 'America/Argentina/Buenos_Aires',

        init() {
            this.tick();
            // sin segundos: basta cada 30s
            setInterval(() => this.tick(), 30000);

            const url = new URL(window.location);
            if (!url.searchParams.has('tz')) {
                url.searchParams.set('tz', this.altTz);
                window.history.replaceState({}, '', url);
            }
        },

        saveTz() { localStorage.setItem('djpanel.tz', this.altTz); this.tick(); },

        fmt(d, tz) {
            return new Intl.DateTimeFormat('es-ES', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
                timeZone: tz,
            }).format(d);
        },

        tick() {
            const now = new Date();
            this.esTime  = this.fmt(now, 'Europe/Madrid');
            this.altTime = this.fmt(now, this.altTz);
        },
    }
}
</script>



