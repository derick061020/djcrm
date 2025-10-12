<div {{ $attributes }}>
    {{ $getChildComponentContainer() }}
</div>
@php
use App\Models\Whatsapp;
    $contactNumber = $getRecord()->contacto;
    $contactName = $getRecord()->name;
    $templates = $this->templates;
    $whatsapp = Whatsapp::where('numero' , $contactNumber)->first();
    if (isset($whatsapp)) {
        $messages = $whatsapp->mensajes;
    }
@endphp

<style>
    .chat-container {
        height: 100%;
        min-height: 500px;
        max-height: 70vh;
        border-radius: 1rem;
        overflow: hidden;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }

    .chat-bubble {
        max-width: 70%;
        border-radius: 1rem;
        padding: 1rem;
        margin: 0.5rem 0;
        position: relative;
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

    .message-time {
        font-size: 0.75rem;
        color: rgba(156, 163, 175, 0.9);
        position: absolute;
        bottom: 0.5rem;
        right: 0.5rem;
    }

    .message-content {
        font-size: 1rem;
        line-height: 1.5;
    }

    .command-input {
        border: 2px solid rgba(156, 163, 175, 0.1);
        transition: all 0.3s ease;
    }

    .command-input:focus {
        border-color: rgba(156, 163, 175, 0.3);
        box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.1);
    }

    /* Ajustes para modo oscuro */
    .dark .chat-container {
        background-color: rgba(var(--gray-800),var(--tw-bg-opacity,1));
    }

    .dark .user-bubble {
        background-color: rgba(156, 163, 175, 0.1);
    }

    .dark .system-bubble {
        background-color: rgba(156, 163, 175, 0.05);
    }

    .dark .message-time {
        color: rgba(255, 255, 255, 0.7);
    }

    .dark .message-content {
        color: rgba(255, 255, 255, 0.9);
    }

    .dark .command-input {
        border-color: rgba(156, 163, 175, 0.1);
    }

    .dark .command-input:focus {
        border-color: rgba(156, 163, 175, 0.3);
        box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.1);
    }
</style>

<div class="chat-container flex flex-col">
    <div class="flex-1 overflow-y-auto p-4" id="chat-container" style="max-height: calc(70vh - 200px);">
        <div class="flex flex-col space-y-4">
            @if(isset($messages))
            @foreach($messages as $msg)
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <div class="chat-bubble {{ $msg['tipo'] === 'recibido' ? 'user-bubble' : 'system-bubble' }}">
                            @if(isset($msg['tipo_mensaje']) && $msg['tipo_mensaje'] === 'archivo')
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <div class="font-medium">{{ $msg['mensaje'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $msg['archivo']['tipo'] }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($msg['fecha'])->format('H:i') }}</div>
                                    </div>
                                    <a href="{{ asset('storage/' . $msg['archivo']['path']) }}" target="_blank" class="text-purple-500 hover:text-purple-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <div class="message-content mt-1">
                                    {{ $msg['mensaje'] }}
                                </div>
                                <span class="message-time">
                                    {{ \Carbon\Carbon::parse($msg['fecha'])->format('H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>

    <!-- Template Selector -->
    <div class="p-4 border-t dark:border-gray-700">
        <div class="mb-4">
            <select wire:model="selectedTemplate" 
                    class="w-full px-3 py-2 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-500">
                <option value="" class="text-gray-500 dark:text-gray-400">Seleccionar plantilla</option>
                @foreach($templates as $template)
                    <option value="{{ $template['id'] }}" class="text-gray-900 dark:text-white bg-white dark:bg-gray-800">
                        {{ $template['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="sticky bottom-0 bg-white dark:bg-gray-800 p-4 border-t dark:border-gray-700">
        <div class="flex flex-col space-y-2">
            @if ($isSendingFile)
                <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                        <span class="text-sm text-blue-700 dark:text-blue-300">Enviando archivo...</span>
                    </div>
                </div>
            @endif
            
            <div class="flex gap-2">
                <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                     x-on:livewire-upload-finish="isUploading = false; $wire.set('file', null, false)"
                     x-on:livewire-upload-error="isUploading = false"
                     x-on:livewire-upload-progress="progress = $event.detail.progress">
                    
                    <input type="file" 
                           wire:model="file" 
                           id="fileInput" 
                           class="hidden"
                           @if(!$isSendingFile) wire:loading.attr="disabled" @endif>
                    
                    <label for="fileInput" 
                           class="flex items-center space-x-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                           :class="{ 'opacity-50 cursor-not-allowed': $wire.isSendingFile }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Archivo</span>
                    </label>
                    
                    <!-- Barra de progreso -->
                    <div x-show="isUploading" class="w-full mt-2">
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                        Subiendo...
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-blue-600" x-text="progress + '%'"></span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                                <div x-bind:style="'width: ' + progress + '%'" 
                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <x-filament::input
                        wire:model.live="message"
                        placeholder="Escribe tu mensaje..."
                        id="message"
                        class="w-full rounded-lg command-input"
                        color="primary"
                        wire:keydown.enter="sendMessage"
                        :disabled="$isSendingFile"
                    />
                </div>
                <x-filament::button
                    color="primary"
                    wire:click="sendMessage"
                    wire:loading.attr="disabled"
                    :disabled="!$message && !$file"
                >
                    <span wire:loading.remove>Enviar</span>
                    <span wire:loading wire:target="sendMessage">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </x-filament::button>
            </div>
        </div>
    </div>

    
</div>

@push('styles')
<style>
    .chat-bubble {
        max-width: 70%;
        border-radius: 1rem;
        padding: 1rem;
        margin: 0.5rem 0;
        position: relative;
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
    
    .message-time {
        font-size: 0.75rem;
        color: rgba(156, 163, 175, 0.9);
        position: absolute;
        bottom: 0.5rem;
        right: 0.5rem;
    }
    
    .message-content {
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .command-input {
        border-color: rgba(156, 163, 175, 0.1);
        transition: all 0.3s ease;
    }
    
    .command-input:focus {
        border-color: rgba(156, 163, 175, 0.3);
        box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.1);
    }
    
    /* Ajustes para modo oscuro */
    .dark .user-bubble {
        background-color: rgba(156, 163, 175, 0.1);
    }
    
    .dark .system-bubble {
        background-color: rgba(156, 163, 175, 0.05);
    }
    
    .dark .message-time {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .dark .message-content {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .dark .command-input {
        border-color: rgba(156, 163, 175, 0.1);
    }
    
    .dark .command-input:focus {
        border-color: rgba(156, 163, 175, 0.3);
        box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-container');
        
        // Función para mantener el scroll al final
        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        scrollToBottom();

        // Auto-scroll cuando se añade un nuevo mensaje
        window.addEventListener('messageAdded', scrollToBottom);

        // Handle Enter key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                @this.sendMessage();
            }
        });

        // Actualizar mensajes cada 3 segundos usando la función loadMessages
        setInterval(() => {
            @this.loadMessages();
            scrollToBottom();
        }, 5000); // Cada 3 segundos

        // Asegurarse de que el scroll se actualice después de cualquier actualización de Livewire
        window.addEventListener('livewire:load', scrollToBottom);
        window.addEventListener('livewire:updated', scrollToBottom);
    });
</script>
@endpush
