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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
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
            "Authorization: Bearer EAAU9Ie8FTL0BPMUbZAlu6ZBmdjwaXmloYwzF8kzKjRgdi2poH093Ha9t0NxCZCb4hQHFZAPUELNAq1dGmYf6wzganz4EnBBD8wkDZBGtHBTU5GgIWdJNyHj66krlvwlZARGo4uKLpJ6yl0ZC71Uue2qZCjF4SU0XTS8k4pZB5IuVpCVY5T363m4KZALJlFV3BnWcZCJerIlFObrLq7cSRrOBopG5lP7iwsfdYWsTzR8ZAjsl4GeDFs4ZD",
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

    
}
