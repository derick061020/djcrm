<x-filament-panels::page>

@php
    function getFirstTwoWords($text) {
        $words = explode(' ', $text);
        return count($words) > 1 ? $words[0] . ' ' . $words[1] : $words[0];
    }

    $data = $this->getMessages();
    $contacts = $data['contacts'];
    $messages = $data['messages'];
@endphp

<style>
    .chat-bubble {
        max-width: 70%;
        border-radius: 1rem;
        padding: 1rem;
        margin: 0.5rem 0;
    }
    
    .user-bubble {
        background-color: rgba(132, 0, 255, 0.1);
        border-bottom-left-radius: 0.25rem;
        margin-right: auto;
    }
    
    .system-bubble {
        background-color: rgba(132, 0, 255, 0.05);
        border-bottom-right-radius: 0.25rem;
        margin-left: auto;
    }
    
    .contact-list {
        width: 250px;
        border-right: 1px solid rgba(156, 163, 175, 0.1);
        height: 100%;
    }
    
    .contact-list .p-4 {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .contact-list .overflow-y-auto {
        height: calc(100% - 3.5rem); /* Altura total menos el header */
    }
    
    .contact-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(156, 163, 175, 0.1);
        cursor: pointer;
        transition: background-color 0.2s;
        position: relative;
    }
    
    .contact-item:hover {
        background-color: rgba(156, 163, 175, 0.05);
    }
    
    .contact-item.active {
        background-color: rgba(132, 0, 255, 0.05);
    }
    
    .unread-badge {
        position: absolute;
        bottom: 0.5rem;
        right: 1rem;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(132, 0, 255, 0.9);
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .contact-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(132, 0, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .unread-badge {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(132, 0, 255, 0.9);
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 0.5rem;
    }
    
    .contact-name {
        font-weight: 500;
    }
    
    .contact-last-message {
        color: rgba(156, 163, 175, 0.9);
        font-size: 0.875rem;
    }
    
    .contact-time {
        color: rgba(156, 163, 175, 0.7);
        font-size: 0.75rem;
    }
    
    /* Ajustes para modo oscuro */
    .dark .contact-list {
        border-right-color: rgba(156, 163, 175, 0.1);
    }
    
    .dark .contact-item:hover {
        background-color: rgba(156, 163, 175, 0.05);
    }
    
    .dark .contact-item.active {
        background-color: rgba(132, 0, 255, 0.1);
    }
    
    .dark .contact-last-message {
        color: rgba(156, 163, 175, 0.7);
    }
    
    .dark .contact-time {
        color: rgba(156, 163, 175, 0.5);
    }


    /* Ajustes para modo oscuro */
    .dark .user-bubble {
        background-color: rgba(156, 163, 175, 0.1);
    }
    
    .dark .system-bubble {
        background-color: rgba(156, 163, 175, 0.05);
    }
    
    .dark .text-filament-primary-600 {
        color: rgba(156, 163, 175, 0.9) !important;
    }
    
    .dark .text-gray-700 {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    .dark .text-gray-500 {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    
    .dark .text-gray-300 {
        color: rgba(255, 255, 255, 0.3) !important;
    }
    
    .command-input {
        border: 2px solid rgba(156, 163, 175, 0.1);
        transition: all 0.3s ease;
    }
    
    .command-input:focus {
        border-color: rgba(156, 163, 175, 0.3);
        box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.1);
    }

    /* Ajustes para modo oscuro del input */
    .dark .command-input {
        border-color: rgba(156, 163, 175, 0.1);
    }
    
    .dark .command-input:focus {
        border-color: rgba(156, 163, 175, 0.3);
        box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.1);
    }
    
    .send-button {
        background: rgba(132, 0, 255, 0.1);
        border: 2px solid rgba(132, 0, 255, 0.1);
        transition: all 0.3s ease;
    }
</style>

<div class="flex flex-col items-center">
    <div class="w-full max-w-4xl">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg" style="height: 580px;">
            <div class="flex" style="height: 580px;">
                <div class="contact-list">
                    <div class="p-4" style="height: 570px;">
                        <h3 class="text-lg font-semibold mb-4">Conversaciones</h3>
                        <div class="overflow-y-auto space-y-0.5">
                            @foreach($contacts as $index => $contact)
                                <div class="contact-item {{ $index === $this->selectedContact ? 'active' : '' }}" 
                                     wire:click="selectContact({{ $index }})"
                                     data-contact="{{ $index }}">
                                    <div class="flex items-center gap-3">
                                        <div class="contact-avatar">
                                            {{ substr($contact['name'], 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="contact-name">{{ $contact['name'] }}</div>
                                                    <div class="contact-last-message">{{ getFirstTwoWords($contact['last_message']) }}</div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="contact-time">{{ $contact['time'] }}</div>
                                                    @if($contact['unread'] > 0)
                                                        <div class="unread-badge">{{ $contact['unread'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex flex-col" style="height: 100%;">
                        <div class="flex flex-col" style="height: 500px;">
                            <div id="chat-container" class="overflow-y-auto p-4 space-y-4" style="height: 100%;">
                            @foreach($messages as $msg)
                                <div class="flex items-start gap-3">
                                    <div class="flex-1">
                                        <div class="chat-bubble {{ $msg['type'] === 'user' ? 'user-bubble' : 'system-bubble' }}">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-sm text-filament-primary-600 dark:text-filament-primary-400">
                                                    {{ $msg['time'] }}
                                                </span>
                                            </div>
                                            <div class="mt-1 text-gray-700 dark:text-gray-300">
                                                {{ $msg['message'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                </div>

                <div class="sticky bottom-0 bg-white dark:bg-gray-800 p-4 border-t dark:border-gray-700">
                    <div class="flex gap-2">
                        <x-filament::input
                            wire:model.live="command"
                            placeholder="Escribe ..."
                            class="flex-1 rounded-lg command-input"
                            color="primary"
                        />
                        <x-filament::button
                            wire:click="executeCommand"
                            color="primary"
                        >
                            Enviar
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-container');
        
        // Función para mantener el scroll al final
        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Auto-scroll cuando se carga la página
        scrollToBottom();

        // Auto-scroll cuando se añade un nuevo mensaje
        window.addEventListener('messageAdded', scrollToBottom);

        // Handle Enter key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                @this.executeCommand();
            }
        });

        // Manejar cambio de chat
        document.querySelectorAll('.contact-item').forEach(item => {
            item.addEventListener('click', function() {
                const contactId = this.getAttribute('data-contact');
                @this.selectContact(contactId);
                
                // Esperar a que Livewire actualice el DOM
                setTimeout(() => {
                    scrollToBottom();
                }, 200);
            });
        });

        // Asegurarse de que el scroll se actualice después de cualquier actualización de Livewire
        window.addEventListener('livewire:load', scrollToBottom);
        window.addEventListener('livewire:updated', scrollToBottom);
    });
</script>
@endpush
</x-filament-panels::page>