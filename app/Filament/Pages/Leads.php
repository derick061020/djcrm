<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\WithPagination;
use App\Models\Clientes as Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Leads extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.leads';
    protected static ?int $navigationSort = 1;

    protected function getLayoutWidth(): string
    {
        return 'full';
    }

    public $categorias = [
        'Pedir requisitos por llamada',
        'Crear presupuesto',
        'Presupuesto creado',
        'Presupuesto Presentado',
        'Primer follow up',
        'Segundo follow up',
        'Tercer follow up',
        'Follow up correo',
        'Eventos pospuestos'
    ];

    public $clientes;
    public $id;
    public $categoria;
    public $nuevaCategoria;

    public function mount()
    {
        $this->clientes = Cliente::all();
    }

    public function updateCategoria($id, $nuevaCategoria)
    {
        $this->id = $id;
        $this->nuevaCategoria = $nuevaCategoria;

        $this->validate([
            'id' => 'required|exists:clientes,id',
            'nuevaCategoria' => 'required|in:' . implode(',', $this->categorias)
        ]);

        DB::beginTransaction();
        try {
            $cliente = Cliente::findOrFail($this->id);
            $cliente->categoria = $this->nuevaCategoria;
            $cliente->save();

            DB::commit();
            $this->clientes = Cliente::all(); // Refrescar la lista de clientes
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getCategorias(): array
    {
        return $this->categorias;
    }

    public function getClientes()
    {
        return $this->clientes;
    }
}
