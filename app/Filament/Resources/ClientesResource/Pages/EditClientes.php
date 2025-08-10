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
    public function sendMessage()
    {
        $url = "https://graph.facebook.com/v22.0/741797439008360/messages";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer EAAU9Ie8FTL0BPNoV5BB8tbCA0uuEaJz5A1mlCi4Kz4CGV4dcRdiIJ4pZATwqEikMCmMxyraJNjuNHKtC6MAqjnLagEFtX4G11KeTfs8uyK1uLwAaM3Fy0ds6g2dtM5TdDYGVyMfHMyurnHWy0pUMaiI3YYihjO3fpqKBjQzTRZCpU2LXb7ycVatNA2jrYbdlm5TZBi0lDpcT6usODE9wKDZChqavYkJ1v6ClO2PTSpeEVZCYZD",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        $data = <<<DATA
            {
            "messaging_product": "whatsapp",    
            "recipient_type": "individual",
            "to": "{$this->record->contacto}",
            "type": "text",
            "text": {
                "preview_url": false,
                "body": "{$this->message}"
            }
        }
        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($httpCode == 200) {
            // Obtener el mensaje actual
            $mensaje = [
                'mensaje' => $this->message,
                'fecha' => now()->format('Y-m-d H:i:s'),
                'tipo' => 'enviado',
                'estado' => 'enviado'
            ];

            // Buscar o crear el registro
            $whatsapp = Whatsapp::firstOrCreate(
                ['numero' => $this->record->contacto],
                ['mensajes' => []]
            );

            // Agregar el nuevo mensaje al array de mensajes
            $mensajes = $whatsapp->mensajes;
            $mensajes[] = $mensaje;
            
            // Actualizar el registro con los nuevos mensajes
            $whatsapp->update([
                'mensajes' => $mensajes
            ]);

            Notification::make()
            ->title('Enviado correctamente')
            ->success()
            ->send();   
        } else {
            Notification::make()
            ->title(json_decode($resp, true)['error']['message'])
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
