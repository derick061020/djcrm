<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function contract(Clientes $cliente)
    {
        // Mark the budget as accepted and record the timestamp
        $cliente->update([
            'contract_accepted' => true,
            'contract_accepted_at' => now(),
            'alternative_requested' => false,
            'alternative_requested_at' => null
        ]);

        // Send notification to all admins and sales operators
        User::where('role', 'admin')
            ->orWhere('role', 'sales_operator')
            ->get()
            ->each(function (User $user) use ($cliente) {
                Notification::make()
                    ->title('Contrato Aceptado')
                    ->body('El cliente ' . $cliente->nombre . ' ha aceptado el contrato para su evento.')
                    ->success()
                    ->sendToDatabase($user);
            });

        // Redirect to a success page or the client's page
        return redirect()->route('presupuesto.index', $cliente->id)->with('success', '¡Presupuesto aceptado con éxito!');
    }

    public function alternative(Clientes $cliente)
    {
        // Mark the budget as needing an alternative and record the timestamp
        $cliente->update([
            'alternative_requested' => true,
            'alternative_requested_at' => now(),
            'contract_accepted' => false,
            'contract_accepted_at' => null
        ]);

        // Send notification to all admins and sales operators
        User::where('role', 'admin')
            ->orWhere('role', 'sales_operator')
            ->get()
            ->each(function (User $user) use ($cliente) {
                Notification::make()
                    ->title('Solicitud de Alternativa')
                    ->body('El cliente ' . $cliente->nombre . ' ha solicitado una alternativa para su evento.')
                    ->warning()
                    ->sendToDatabase($user);
            });

        // Redirect to a success page or the client's page
        return redirect()->route('presupuesto.index', $cliente->id)->with('success', '¡Solicitud de alternativa enviada!');
    }
}
