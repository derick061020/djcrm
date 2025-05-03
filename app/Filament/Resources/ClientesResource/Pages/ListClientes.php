<?php

namespace App\Filament\Resources\ClientesResource\Pages;

use App\Filament\Resources\ClientesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListClientes extends ListRecords
{
    protected static string $resource = ClientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if (auth()->user()->hasRole('data_manager')) {
            $query->where('estado', false);
        } elseif (auth()->user()->hasRole('sales_operator')) {
            $query->where('estado', true)
                ->where('contract_accepted', false);
        } elseif (auth()->user()->hasRole('event_manager')) {
            $query->where('contract_accepted', true)->where('agendado', false);
        }

        return $query;
    }
}
