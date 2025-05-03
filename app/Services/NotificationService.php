<?php

namespace App\Services;

use App\Models\Notificaciones;
use App\Models\Clientes;

class NotificationService
{
    public function createNotification(
        string $tipo,
        string $mensaje,
        \App\Models\User $user,
        ?Clientes $cliente = null,
        array $data = []
    ): Notificaciones {
        return Notificaciones::create([
            'user_id' => $user->id,
            'cliente_id' => $cliente?->id,
            'tipo' => $tipo,
            'mensaje' => $mensaje,
            'data' => $data,
            'leido' => false
        ]);
    }

    public function markAsRead(Notificaciones $notification): void
    {
        $notification->update(['leido' => true]);
    }

    public function getUnreadNotifications(\App\Models\User $user): \Illuminate\Database\Eloquent\Collection
    {
        return Notificaciones::where('user_id', $user->id)
            ->where('leido', false)
            ->with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAllNotifications(\App\Models\User $user): \Illuminate\Database\Eloquent\Collection
    {
        return Notificaciones::where('user_id', $user->id)
            ->with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
