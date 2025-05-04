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
        $url = "https://graph.facebook.com/v22.0/656799494179884/messages";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer EAAhcUrJGdZBoBOzMdYb9bxiSDhL8W11eegHtwgMzmdW57TS4AwPLgMj6b1wte4mPwV6UhPDZBL8soFkNZA7WKwy1quQGREBxEJlkoz5In1rak0wU6lbUna4Xomuk03jTjc0uuLm5FIglCd8cilwJGQZB13s9XCUrYZAZAoUIdRPoifoevQp7ZC6VzOmDvQJdhRkketBa26AqMcCWMyBcKa69smmVwcZD",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = <<<DATA
        {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": "{$this->record->contacto}",
            "type": "text",
            "text" : {
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
