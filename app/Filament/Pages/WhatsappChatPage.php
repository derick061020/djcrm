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
    public $templates = [];
    public $selectedTemplate = '';
    
    public function boot()
    {
        $this->whatsappService = app(WhatsAppService::class);
        $this->templates = \App\Models\WhatsappTemplate::where('is_active', true)
            ->get()
            ->map(function($template) {
                return [
                    'id' => $template->id,
                    'name' => $template->name,
                    'content' => $template->content
                ];
            });
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
            $this->addError('message', 'El mensaje no puede estar vacío');
            return;
        }

        $whatsappService = app(WhatsAppService::class);
        
        try {
            $response = $whatsappService->sendMessage($this->contactNumber, $this->message);
            
            if (isset($response['messages'][0]['id'])) {
                $this->messages[] = [
                    'type' => 'system',
                    'message' => $this->message,
                    'time' => now()->format('H:i'),
                ];
                
                $this->message = '';
                $this->emit('messageSent');
            } else {
                $this->addError('message', 'Error al enviar el mensaje');
            }
        } catch (\Exception $e) {
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

    public function updatedSelectedTemplate($value)
    {
        if ($value) {
            $this->applyTemplate($value);
        }
    }

    public function applyTemplate($templateId)
    {
        $template = \App\Models\WhatsappTemplate::find($templateId);
        if ($template) {
            $this->message = str_replace('{{ nombre }}', $this->contactName, $template->content);
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
