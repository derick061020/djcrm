{{-- Monta el componente Livewire en la topbar --}}
<div class="dj-topbar-center">
    <livewire:topbar-stats />
</div>

<style>
/* ——— Centrado del hook en la topbar ——— */
.fi-topbar > div:first-child { position: relative; }
.dj-topbar-center {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  align-items: center;
}

/* ——— Píldoras: compacto y consistente con el UI ——— */
.dj-topbar-pills { gap: .55rem; font-size: .8125rem; line-height: 1.2; }

.dj-pill{
  display: flex;
  align-items: center;
  height: 32px;
  padding: .25rem .6rem;
  border-radius: 9999px;
  background: #fff;
  border: 1px solid #e5e7eb;
}
.dark .dj-pill{
  background: rgba(17,24,39,.5);
  border-color: rgba(255,255,255,.08);
}

/* Números tabulares (hora estable) */
.tabular-nums{ font-variant-numeric: tabular-nums; font-feature-settings: 'tnum' 1; }

/* Select del país: simple y sin brillo */
.dj-pill select{
  background: transparent;
  border: 0;
  padding-right: .25rem;
  font-size: inherit;
  outline: none;
}
.dj-pill select:focus{ outline: none; box-shadow: none; }
</style>



