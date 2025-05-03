<?php

namespace App\Notifications;

use App\Models\Clientes;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action;

class NewJobAssignedNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Clientes $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Send the notification using Filament's notification system.
     */
    public function send(): void
    {
        FilamentNotification::make()
            ->title('Nuevo Trabajo Asignado')
            ->body("Se te ha asignado un nuevo trabajo para el cliente: {$this->cliente->nombre}")
            ->success()
            ->actions([
                Action::make('ver_detalles')
                    ->label('Ver Detalles')
                    ->url('/crm/clientes/' . $this->cliente->id),
            ])
            ->send();
    }
}
