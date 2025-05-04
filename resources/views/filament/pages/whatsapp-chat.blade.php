@php
    $contactNumber = $getRecord()->contacto;
    $contactName = $getRecord()->name;
@endphp

<style>
    .chat-container {
        height: 100%;
        min-height: 500px;
        border-radius: 1rem;
        overflow: hidden;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

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

    .message-time {
        font-size: 0.875rem;
        color: rgba(156, 163, 175, 0.9);
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
    }
</style>

<div class="chat-container flex flex-col">
    <div class="flex-1 overflow-y-auto p-4" id="chat-container">
        <div class="flex flex-col space-y-4">
            @if(isset($messages))
            @foreach($messages as $msg)
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <div class="chat-bubble {{ $msg['type'] === 'user' ? 'user-bubble' : 'system-bubble' }}">
                            <div class="flex items-center gap-2">
                                <span class="message-time">
                                    {{ $msg['time'] }}
                                </span>
                            </div>
                            <div class="message-content mt-1">
                                {{ $msg['message'] }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="sticky bottom-0 bg-white dark:bg-gray-800 p-4 border-t dark:border-gray-700">
        <div class="flex gap-2">
            <x-filament::input
                wire:model.live="message"
                placeholder="Escribe tu mensaje..."
                class="flex-1 rounded-lg command-input"
                color="primary"
            />
            <x-filament::button
                wire:click="sendMessage"
                color="primary"
            >
                Enviar
            </x-filament::button>
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
    
    /* Ajustes para modo oscuro */
    .dark .user-bubble {
        background-color: rgba(156, 163, 175, 0.1);
    }
    
    .dark .system-bubble {
        background-color: rgba(156, 163, 175, 0.05);
    }
    
    .command-input {
        border-color: rgba(156, 163, 175, 0.1);
        transition: all 0.3s ease;
    }
    
    .command-input:focus {
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

        // Asegurarse de que el scroll se actualice después de cualquier actualización de Livewire
        window.addEventListener('livewire:load', scrollToBottom);
        window.addEventListener('livewire:updated', scrollToBottom);
    });
</script>
@endpush
