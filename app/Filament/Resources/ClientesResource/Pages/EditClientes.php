<?php

namespace App\Filament\Resources\ClientesResource\Pages;

use App\Filament\Resources\ClientesResource;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Whatsapp;

class EditClientes extends EditRecord
{
    protected static string $resource = ClientesResource::class;
    public $message = '';
    public $file;
    public $templates = [];
    public $selectedTemplate = '';
    
    protected $rules = [
        'file' => 'nullable|file|max:10240', // 10MB max
    ];
    
    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Cliente #' . str_pad($this->record->id, 4, '0', STR_PAD_LEFT);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
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
            $this->message = str_replace('{{ nombre }}', $this->record->nombre, $template->content);
        }
    }
    public function updatedFile()
    {
        $this->validateOnly('file');
    }

    public function sendMessage()
    {
        $phoneNumber = env('WHATSAPP_PHONE_NUMBER');
        $whatsappToken = env('WHATSAPP_API_TOKEN');
        
        $url = "https://graph.facebook.com/v22.0/{$phoneNumber}/messages";

        if ($this->file) {
            // Guardar el archivo
            $path = $this->file->store('whatsapp-files', 'public');
            $fileUrl = asset('storage/' . $path);
            
            // Determinar el tipo de archivo
            $mimeType = $this->file->getMimeType();
            $fileType = $this->getFileType($mimeType);
            
            // Crear mensaje para la base de datos
            $mensaje = [
                'mensaje' => $this->file->getClientOriginalName(),
                'fecha' => now()->format('Y-m-d H:i:s'),
                'tipo' => 'enviado',
                'tipo_mensaje' => 'archivo',
                'archivo' => [
                    'tipo' => $fileType,
                    'path' => $path,
                    'mime_type' => $mimeType,
                    'nombre' => $this->file->getClientOriginalName()
                ]
            ];
            
            // Configurar datos para la API de WhatsApp
            $data = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => '+'.$this->record->contacto,
                'type' => $fileType === 'document' ? 'document' : $fileType,
                $fileType === 'document' ? 'document' : $fileType => [
                    'link' => $fileUrl,
                    'caption' => $this->message ?: null
                ]
            ];
        } else {
            // Mensaje de texto normal
            $data = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => '+'.$this->record->contacto,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $this->message
                ]
            ];
            
            $mensaje = [
                'mensaje' => $this->message,
                'fecha' => now()->format('Y-m-d H:i:s'),
                'tipo' => 'enviado',
                'estado' => 'enviado'
            ];
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $whatsappToken,
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($httpCode == 200) {
            // Buscar o crear el registro
            $whatsapp = Whatsapp::firstOrCreate(
                ['numero' => $this->record->contacto],
                ['mensajes' => []]
            );

            // Agregar el nuevo mensaje al array de mensajes
            $mensajes = $whatsapp->mensajes;
            $mensajes[] = $mensaje;
            
            // Actualizar el registro con los nuevos mensajes
            $whatsapp->update(['mensajes' => $mensajes]);

            Notification::make()
                ->title('Mensaje enviado correctamente')
                ->success()
                ->send();
                
            // Limpiar los campos
            $this->reset(['message', 'file']);
            
        } else {
            $errorMessage = json_decode($response, true)['error']['message'] ?? 'Error al enviar el mensaje';
            Notification::make()
                ->title($errorMessage)
                ->danger()
                ->send();
        }
    }

    private function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } else {
            return 'document';
        }
    }
    
    public $mensajes = [];

    public function loadMessages()
    {
        $whatsapp = Whatsapp::where('numero', $this->record->contacto)->first();
        if ($whatsapp) {
            $this->mensajes = $whatsapp->mensajes;
        }
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
    
}
