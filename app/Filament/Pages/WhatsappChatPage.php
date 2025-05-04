<?php

namespace App\Filament\Pages;

use App\Services\WhatsAppService;
use Filament\Pages\Page;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class WhatsappChatPage extends Page
{
    use WithPagination;

    protected static string $view = 'filament.pages.whatsapp-chat';

    public string $message = '';
    public string $contactNumber = '';
    public string $contactName = '';
    protected static bool $shouldRegisterNavigation = false;
    protected $whatsappService;
    protected $messages = [];
    
    public function boot()
    {
        $this->whatsappService = app(WhatsAppService::class);
    }
    
    public function mount()
    {
        $record = $getRecord();
        $this->contactNumber = $record->contacto;
        $this->contactName = $record->name;
        
        // Fetch messages when the component mounts
        $this->refreshMessages();
    }

    /**
     * Send a message via WhatsApp API
     */
    public function sendMessage()
    {
        if (empty($this->message)) {
            return;
        }
        
        try {
            $sent = $this->whatsappService->sendMessage($this->contactNumber, $this->message);
            
            if ($sent) {
                // Add the message to the local messages array for immediate display
                $this->messages[] = [
                    'type' => 'system',
                    'message' => $this->message,
                    'time' => now()->format('H:i'),
                ];
                
                $this->message = '';
                $this->dispatch('messageAdded');
            } else {
                // Handle send failure
                $this->addError('message', 'No se pudo enviar el mensaje. Intenta de nuevo más tarde.');
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp message: ' . $e->getMessage());
            $this->addError('message', 'Error al enviar el mensaje: ' . $e->getMessage());
        }
    }

    /**
     * Refresh messages from the WhatsApp API
     */
    public function refreshMessages()
    {
        try {
            $this->messages = $this->whatsappService->getMessages($this->contactNumber);
            $this->dispatch('messagesRefreshed');
        } catch (\Exception $e) {
            Log::error('Error fetching WhatsApp messages: ' . $e->getMessage());
            $this->addError('messages', 'Error al cargar los mensajes: ' . $e->getMessage());
        }
    }
    
    /**
     * Get the messages to display in the chat
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function getModalWidth(): string
    {
        return 'xl';
    }
}
