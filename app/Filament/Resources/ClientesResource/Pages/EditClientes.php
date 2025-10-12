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
    public function updatedFile($value)
    {
        $this->validate([
            'file' => 'max:10240', // 10MB Max
        ]);
    }

    public function sendMessage()
    {
        $phoneNumber = env('WHATSAPP_PHONE_NUMBER');
        $whatsappToken = env('WHATSAPP_API_TOKEN');
        
        $url = "https://graph.facebook.com/v22.0/{$phoneNumber}/messages";

        if ($this->file) {
            // Manejar envío de archivo
            $filePath = $this->file->store('whatsapp-files');
            $fileUrl = asset('storage/' . $filePath);
            $mimeType = $this->file->getMimeType();
            $fileName = $this->file->getClientOriginalName();
            $fileExtension = $this->file->getClientOriginalExtension();
            
            // Determinar el tipo de mensaje basado en el MIME type
            $messageType = $this->getMessageType($mimeType);
            
            $data = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => '+'.$this->record->contacto,
                'type' => $messageType,
                $messageType => [
                    'link' => $fileUrl,
                    'caption' => $this->message,
                    'filename' => $fileName,
                ]
            ];
            
            $mensaje = [
                'mensaje' => $fileName,
                'fecha' => now()->format('Y-m-d H:i:s'),
                'tipo' => 'enviado',
                'tipo_mensaje' => 'archivo',
                'archivo' => [
                    'tipo' => $this->getFileType($mimeType),
                    'path' => $filePath,
                    'mime_type' => $mimeType,
                    'nombre' => $fileName,
                    'extension' => $fileExtension
                ]
            ];
        } else {
            // Manejar mensaje de texto normal
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
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$whatsappToken}",
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        
        // Deshabilitar verificación SSL para desarrollo
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response = json_decode($resp, true);
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
                
            // Limpiar los inputs
            $this->reset(['message', 'file']);
        } else {
            $errorMessage = $response['error']['message'] ?? 'Error al enviar el mensaje';
            Notification::make()
                ->title($errorMessage)
                ->danger()
                ->send();
        }
    }
    
    private function getMessageType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif (in_array($mimeType, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return 'document';
        }
        
        return 'document'; // Por defecto
    }
    
    private function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'imagen';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif ($mimeType === 'application/pdf') {
            return 'PDF';
        } elseif (in_array($mimeType, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return 'Documento Word';
        } elseif (in_array($mimeType, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return 'Hoja de cálculo';
        }
        
        return 'archivo';
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
