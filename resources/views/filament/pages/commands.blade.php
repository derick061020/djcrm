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

    .max-w-6xl {
        max-width: 700rem;
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
    <div class="w-full max-w-6xl">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg" style="height: 580px;">
            <div class="flex" style="height: 580px;">
                <!-- Lista de contactos (25% ancho) -->
                <div class="contact-list " style="width: 20%;">
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
                
                <!-- Área de mensajes (50% ancho) -->
                <div class="flex-1 w-2/4">
                    <div class="flex flex-col" style="height: 100%;">
                        <div class="flex flex-col" style="height: 410px;">
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

                            <!-- Template Selector -->
                <div class="p-4 border-t dark:border-gray-700">
                    <div class="mb-4">
                        <select wire:model="selectedTemplate" 
                                class="w-full px-3 py-2 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-500">
                            <option value="" class="text-gray-500 dark:text-gray-400">Seleccionar plantilla</option>
                            @foreach($data['templates'] as $template)
                                <option value="{{ $template['id'] }}" class="text-gray-900 dark:text-white bg-white dark:bg-gray-800">
                                    {{ $template['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="sticky bottom-0 bg-white dark:bg-gray-800 p-4 border-t dark:border-gray-700">
                    <div class="flex gap-2">
                        
                        <input type="file" wire:model="file" id="fileInput" class="hidden">
                        <label for="fileInput" class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-700">Archivo</span>
                        </label>
                        <x-filament::input
                            wire:model.live="message"
                            placeholder="Escribe tu mensaje..."
                            id="message"
                            class="flex-1 rounded-lg command-input"
                            color="primary"
                        />
                        <x-filament::button
                            color="primary"
                            wire:click="executeCommand()"
                        >
                            Enviar
                        </x-filament::button>
                    
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Panel de información del cliente (25% ancho) -->
        <div class="client-info-panel w-1/4 border-l border-gray-200 dark:border-gray-700 p-4 overflow-y-auto">
            @if($data['selected_client'])
                <div class="bg-indigo-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    <h3 class="font-bold text-lg text-indigo-800 dark:text-indigo-200 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Información del Cliente
                    </h3>
                    
                    <div class="space-y-4 mt-3">
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <div class="font-medium text-indigo-600 dark:text-indigo-300 mb-1">Datos Básicos</div>
                            <div class="grid grid-cols-1 gap-2">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Nombre Completo</div>
                                    <div class="font-medium">{{ $data['selected_client']['nombre'] ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Contacto</div>
                                    <div class="font-medium">{{ $data['selected_client']['contacto'] ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <div class="font-medium text-indigo-600 dark:text-indigo-300 mb-1">Detalles del Evento</div>
                            <div class="grid grid-cols-1 gap-2">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Tipo de Evento</div>
                                    <div class="font-medium">{{ $data['selected_client']['tipo_evento'] ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Fecha Estimada</div>
                                    <div class="font-medium text-sm">
                                        @if(!empty($data['selected_client']['fecha_estimada']))
                                            {{ \Carbon\Carbon::parse($data['selected_client']['fecha_estimada'])->locale('es')->isoFormat('dddd D [de] MMMM [a las] h:mmA') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 dark:text-gray-400 mt-8 flex flex-col items-center">
                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Seleccione un contacto para ver la información
                </div>
            @endif
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