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
    public $sendingFile = false;
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
    public function sendFile()
    {
        if (!$this->file) {
            return;
        }

        $phoneNumber = env('WHATSAPP_PHONE_NUMBER');
        $whatsappToken = env('WHATSAPP_API_TOKEN');
        $url = "https://graph.facebook.com/v22.0/{$phoneNumber}/messages";

        // Guardar el archivo
        $path = $this->file->store('whatsapp-files', 'public');
        $fileUrl = asset('storage/' . $path);
        
        // Determinar el tipo de archivo
        $mimeType = $this->file->getMimeType();
        $fileType = str_starts_with($mimeType, 'image/') ? 'image' : 'document';

        // Configurar datos para la API de WhatsApp
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '+'.$this->record->contacto,
            'type' => $fileType,
            $fileType => [
                'link' => $fileUrl
            ]
        ];

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $whatsappToken
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($httpCode == 200) {
            // Guardar en la base de datos
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

            $whatsapp = Whatsapp::firstOrCreate(
                ['numero' => $this->record->contacto],
                ['mensajes' => []]
            );

            $mensajes = $whatsapp->mensajes;
            $mensajes[] = $mensaje;
            $whatsapp->update(['mensajes' => $mensajes]);

            Notification::make()
                ->title($response)
                ->success()
                ->send();
                
            $this->reset('file');
        } else {
            Notification::make()
                ->title('Error al enviar el archivo')
                ->danger()
                ->send();
        }
    }

    public function sendMessage()
    {
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
            'to' => '+'.$this->record->contacto,
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
            $whatsapp->update(['mensajes' => $mensajes]);

            Notification::make()
                ->title('Mensaje enviado')
                ->success()
                ->send();
                
            $this->reset('message');
        } else {
            Notification::make()
                ->title('Error al enviar el mensaje')
                ->danger()
                ->send();
        }
        // Limpiar el input
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
