<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Concerns\HasTable;
use Filament\Actions\Action;
use Livewire\WithPagination;
use App\Models\Whatsapp;
use App\Models\Clientes;

class CommandsPage extends Page
{
    use WithPagination;

    public $selectedContact = 0;

    protected static ?string $navigationLabel = 'Mensajes';
    protected static ?string $title = 'Mensajes';
    protected static ?int $navigationSort = 3;
    protected static ?string $description = 'Ejecuta comandos del sistema';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-right';

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
        // Obtener todos los contactos de WhatsApp
        $whatsappContacts = Whatsapp::all();

        // Formatear contactos para la vista
        $contacts = $whatsappContacts->map(function($contact, $index) {
            $mensajes = $contact->mensajes ?? [];
            
            // Buscar cliente por número de contacto
            $cliente = Clientes::where('contacto', $contact->numero)->first();
            
            // Obtener último mensaje
            $ultimoMensaje = end($mensajes);
            
            return [
                'id' => $index,
                'name' => $cliente ? '#' . str_pad($cliente->id, 4, '0', STR_PAD_LEFT) : $contact->numero,
                'last_message' => $ultimoMensaje['mensaje'] ?? 'Sin mensajes',
                'time' => $ultimoMensaje['fecha'] ?? '',
                'unread' => 0,
                'has_messages' => !empty($mensajes),
                'cliente_data' => $cliente ? $cliente->toArray() : null
            ];
        })->toArray();

        // Obtener mensajes y datos del contacto seleccionado
        $selectedIndex = $this->selectedContact;
        $selectedContact = $whatsappContacts[$selectedIndex] ?? null;
        
        $dbMessages = $selectedContact ? ($selectedContact->mensajes ?? []) : [];
        
        $messages = array_map(function($msg) {
            return [
                'type' => $msg['tipo'] === 'enviado' ? 'system' : 'user',
                'message' => $msg['mensaje'],
                'time' => $msg['fecha']
            ];
        }, $dbMessages);

        $templates = \App\Models\WhatsappTemplate::where('is_active', true)
        ->get()
        ->map(function($template) {
            return [
                'id' => $template->id,
                'name' => $template->name,
                'content' => $template->content
            ];
        });
        return [
            'contacts' => $contacts,
            'messages' => $messages,
            'selected_client' => $contacts[$selectedIndex]['cliente_data'] ?? null,
            'templates' => $templates
        ];
    }
}