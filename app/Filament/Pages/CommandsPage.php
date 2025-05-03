<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Concerns\HasTable;
use Filament\Actions\Action;
use Livewire\WithPagination;

class CommandsPage extends Page
{
    use WithPagination;

    public $selectedContact = 0;

    protected static ?string $navigationGroup = 'Sistema';
    protected static ?string $navigationLabel = 'Mensajes';
    protected static ?string $title = 'Mensajes';
    protected static ?string $description = 'Ejecuta comandos del sistema';

    public function getTitle(): string
    {
        return 'Mensajes';
    }

    protected static string $view = 'filament.pages.commands';

    public function selectContact($contactId)
    {
        $this->selectedContact = $contactId;
    }

    public function getMessages()
    {
        $contacts = [
            ['name' => 'Cliente 1', 'last_message' => 'Hola, necesito ayuda con mi pedido.', 'time' => '14:30', 'unread' => 2],
            ['name' => 'Cliente 2', 'last_message' => '¿Cuándo llegará mi pedido?', 'time' => '14:25', 'unread' => 0],
            ['name' => 'Cliente 3', 'last_message' => 'Tengo un problema con mi cuenta.', 'time' => '14:15', 'unread' => 1],
            ['name' => 'Cliente 4', 'last_message' => 'Necesito cambiar mi dirección.', 'time' => '14:05', 'unread' => 0],
            ['name' => 'Cliente 5', 'last_message' => '¿Cuándo estará disponible el producto?', 'time' => '13:50', 'unread' => 3],
        ];

        $messages = [
            0 => [
                ['type' => 'user', 'message' => 'Hola, necesito ayuda con mi pedido.', 'time' => '14:30'],
                ['type' => 'system', 'message' => '¡Hola! ¿Podrías proporcionarme el número de pedido?', 'time' => '14:30'],
                ['type' => 'user', 'message' => 'Claro, es el pedido #123456.', 'time' => '14:31'],
                ['type' => 'system', 'message' => '¡Perfecto! Veo que tu pedido está en proceso de envío.', 'time' => '14:31'],
                ['type' => 'system', 'message' => 'El paquete será entregado mañana entre las 10:00 y las 14:00.', 'time' => '14:32'],
            ],
            1 => [
                ['type' => 'user', 'message' => '¿Cuándo llegará mi pedido?', 'time' => '14:25'],
                ['type' => 'system', 'message' => 'El paquete ya está en camino.', 'time' => '14:25'],
                ['type' => 'system', 'message' => 'Se entregará mañana entre las 10:00 y las 14:00.', 'time' => '14:25'],
            ],
            2 => [
                ['type' => 'user', 'message' => 'Tengo un problema con mi cuenta.', 'time' => '14:15'],
                ['type' => 'system', 'message' => '¡Hola! ¿Podrías proporcionarme más detalles?', 'time' => '14:15'],
                ['type' => 'user', 'message' => 'No puedo iniciar sesión.', 'time' => '14:16'],
                ['type' => 'system', 'message' => 'Por favor, verifica tu correo electrónico.', 'time' => '14:16'],
            ],
            3 => [
                ['type' => 'user', 'message' => 'Necesito cambiar mi dirección.', 'time' => '14:05'],
                ['type' => 'system', 'message' => '¡Hola! ¿Podrías proporcionarme la nueva dirección?', 'time' => '14:05'],
            ],
            4 => [
                ['type' => 'user', 'message' => '¿Cuándo estará disponible el producto?', 'time' => '13:50'],
                ['type' => 'system', 'message' => 'El producto estará disponible la próxima semana.', 'time' => '13:50'],
                ['type' => 'system', 'message' => '¿Te gustaría que te avisemos cuando esté disponible?', 'time' => '13:50'],
            ],
        ];

        return [
            'contacts' => $contacts,
            'messages' => $messages[$this->selectedContact] ?? [],
        ];
    }
}
