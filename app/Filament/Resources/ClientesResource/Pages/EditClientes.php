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
    public $isSendingFile = false;
    
    public function mount($record): void
    {
        parent::mount($record);
        $this->isSendingFile = false;
    }
    
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
        $this->validate([
            'file' => 'file|max:10240', // Máximo 10MB
        ]);

        $this->isSendingFile = true;
        $this->sendFile($this->file);
    }

    public function sendFile($file)
    {
        try {
            $phoneNumber = env('WHATSAPP_PHONE_NUMBER');
            $whatsappToken = env('WHATSAPP_API_TOKEN');
            
            // Subir el archivo a un almacenamiento temporal
            $path = $file->store('temp', 'public');
            $mimeType = $file->getMimeType();
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            
            // Determinar el tipo de archivo
            $fileType = $this->getFileType($mimeType);
            
            $url = "https://graph.facebook.com/v22.0/{$phoneNumber}/messages";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                "Authorization: Bearer {$whatsappToken}",
                "Content-Type: application/json"
            ];
            
            // Obtener la URL pública del archivo
            $fileUrl = asset('storage/' . $path);
            
            $data = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => '+' . $this->record->contacto,
                'type' => $fileType,
                $fileType => [
                    'link' => $fileUrl,
                    'caption' => $this->message ?: 'Archivo adjunto',
                    'filename' => $fileName
                ]
            ];

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $response = json_decode($resp, true);
            curl_close($curl);
            
            if ($httpCode == 200) {
                // Guardar el mensaje en la base de datos
                $mensaje = [
                    'mensaje' => $fileName,
                    'fecha' => now()->format('Y-m-d H:i:s'),
                    'tipo' => 'enviado',
                    'estado' => 'enviado',
                    'tipo_mensaje' => 'archivo',
                    'archivo' => [
                        'path' => $path,
                        'tipo' => $fileType,
                        'tamano' => $fileSize,
                        'mime_type' => $mimeType
                    ]
                ];

                $whatsapp = Whatsapp::firstOrCreate(
                    ['numero' => $this->record->contacto],
                    ['mensajes' => []]
                );

                $mensajes = $whatsapp->mensajes;
                $mensajes[] = $mensaje;
                
                $whatsapp->update(['mensajes' => $mensajes]);

                Notification::make()
                    ->title('Archivo enviado correctamente')
                    ->success()
                    ->send();
            } else {
                throw new \Exception($response['error']['message'] ?? 'Error al enviar el archivo');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al enviar el archivo: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->isSendingFile = false;
            $this->file = null;
            $this->message = '';
        }
    }

    protected function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif ($mimeType === 'application/pdf') {
            return 'document';
        } else {
            return 'document'; // Por defecto como documento
        }
    }

    public function sendMessage()
    {
        if ($this->file) {
            $this->sendFile($this->file);
            return;
        }

        $phoneNumber = env('WHATSAPP_PHONE_NUMBER');
        $whatsappToken = env('WHATSAPP_API_TOKEN');
        
        $url = "https://graph.facebook.com/v22.0/{$phoneNumber}/messages";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer {$whatsappToken}",
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '+' . $this->record->contacto,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $this->message
            ]
        ];

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response = json_decode($resp, true);
        curl_close($curl);
        
        if ($httpCode == 200) {
            $mensaje = [
                'mensaje' => $this->message,
                'fecha' => now()->format('Y-m-d H:i:s'),
                'tipo' => 'enviado',
                'estado' => 'enviado'
            ];

            $whatsapp = Whatsapp::firstOrCreate(
                ['numero' => $this->record->contacto],
                ['mensajes' => []]
            );

            $mensajes = $whatsapp->mensajes;
            $mensajes[] = $mensaje;
            
            $whatsapp->update([
                'mensajes' => $mensajes
            ]);

            Notification::make()
                ->title('Mensaje enviado correctamente')
                ->success()
                ->send();   
        } else {
            Notification::make()
                ->title($response['error']['message'] ?? 'Error al enviar el mensaje')
                ->danger()
                ->send();
        }
        
        $this->message = '';
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
