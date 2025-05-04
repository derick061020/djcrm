<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    private const WEBHOOK_VERIFY_TOKEN = 'HolaNovato';
    private const GRAPH_API_TOKEN = 'EAAhcUrJGdZBoBOzMdYb9bxiSDhL8W11eegHtwgMzmdW57TS4AwPLgMj6b1wte4mPwV6UhPDZBL8soFkNZA7WKwy1quQGREBxEJlkoz5In1rak0wU6lbUna4Xomuk03jTjc0uuLm5FIglCd8cilwJGQZB13s9XCUrYZAZAoUIdRPoifoevQp7ZC6VzOmDvQJdhRkketBa26AqMcCWMyBcKa69smmVwcZD';
    private const BUSINESS_PHONE_NUMBER_ID = '656799494179884';

    
    public function recibe(Request $request)
    {
        // Log incoming messages
        Log::info("Incoming webhook message:", [
            'body' => json_encode($request->all(), JSON_PRETTY_PRINT)
        ]);

        // Check if the webhook request contains a message
        $message = $request->json('entry.0.changes.0.value.messages.0');
        print($message);

        // Check if the incoming message contains text
        if ($message && $message['type'] === 'text') {
            // Extract the business number to send the reply from it
            $business_phone_number_id = $request->json('entry.0.changes.0.value.metadata.phone_number_id');

            // Send a reply message
            Http::withHeaders([
                'Authorization' => 'Bearer ' . self::GRAPH_API_TOKEN,
            ])->post("https://graph.facebook.com/v22.0/{$business_phone_number_id}/messages",
                [
                    'messaging_product' => 'whatsapp',
                    'to' => $message['from'],
                    'text' => ['body' => "Echo: " . $message['text']['body']],
                    'context' => [
                        'message_id' => $message['id']
                    ]
                ]
            );

            // Mark incoming message as read
            Http::withHeaders([
                'Authorization' => 'Bearer ' . self::GRAPH_API_TOKEN,
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
