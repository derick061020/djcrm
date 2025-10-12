<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Whatsapp;

class WebhookController extends Controller
{
    private const WEBHOOK_VERIFY_TOKEN = 'HolaNovato';
    private string $graphApiToken;
    private string $businessPhoneNumberId;

    public function __construct()
    {
        $this->graphApiToken = env('WHATSAPP_API_TOKEN');
        $this->businessPhoneNumberId = env('WHATSAPP_BUSINESS_PHONE_ID', '656799494179884');
    }

    
    public function recibe(Request $request)
    {
        // Log incoming messages
        Log::info("Incoming webhook message:", [
            'body' => json_encode($request->all(), JSON_PRETTY_PRINT)
        ]);

        // Check if the webhook request contains a message
        $message = $request->json('entry.0.changes.0.value.messages.0');
        
        if ($message && $message['type'] === 'text') {
            // Extraer información del mensaje
            $from = $message['from'];
            $body = $message['text']['body'];
            $timestamp = $message['timestamp'];
            
            // Crear mensaje recibido
            $mensaje = [
                'mensaje' => $body,
                'fecha' => date('Y-m-d H:i:s', $timestamp),
                'tipo' => 'recibido',
                'estado' => 'recibido'
            ];

            // Buscar o crear el registro
            $whatsapp = Whatsapp::firstOrCreate(
                ['numero' => $from],
                ['mensajes' => []]
            );

            // Agregar el nuevo mensaje al array de mensajes
            $mensajes = $whatsapp->mensajes;
            $mensajes[] = $mensaje;
            
            // Actualizar el registro con los nuevos mensajes
            $whatsapp->update([
                'mensajes' => $mensajes
            ]);

            // Marcar mensaje como leído
            $business_phone_number_id = $request->json('entry.0.changes.0.value.metadata.phone_number_id');
            Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->graphApiToken,
            ])->post("https://graph.facebook.com/v22.0/{$business_phone_number_id}/messages",[
                    'messaging_product' => 'whatsapp',
                    'status' => 'read',
                    'message_id' => $message['id']
                ]
            );
        }

        return response(200);
    }

    public function webhook(Request $request)
    {
      
        $mode = $_GET['hub_mode'];
        $token = $_GET['hub_verify_token'];
        $challenge = $_GET['hub_challenge'];

        // Check the mode and token sent are correct $mode == 'subscribe' &&
        if ( $token === 'HolaNovato') {
            // Respond with 200 OK and challenge token from the request
            return response($challenge, 200);
        }

        // Respond with '403 Forbidden' if verify tokens do not match
        return response()->json(['error' => 'Invalid verification token'], 403);
    }

    
}
