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

    public function webhook(Request $request)
    {
        $mode = $request->query('hub.mode');
        $token = $request->query('hub.verify_token');
        $challenge = $request->query('hub.challenge');

        if ($mode === 'subscribe' && $token === self::WEBHOOK_VERIFY_TOKEN) {
            return response($challenge, 200);
        }

        return response()->json(['error' => 'Invalid verification token'], 403);
    }

    public function recibe(Request $request)
    {
        try {
            $data = $request->json()->all();
            
            if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
                $message = $data['entry'][0]['changes'][0]['value']['messages'][0];
                
                if ($message['type'] === 'text') {

                }
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Error processing webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    
}
