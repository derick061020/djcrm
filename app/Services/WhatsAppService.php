<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $baseUrl = 'https://graph.facebook.com/v22.0';
    private string $phoneId = '656799494179884';
    private string $accessToken;

    public function __construct()
    {
        $this->accessToken = config('services.whatsapp.access_token');
    }

    /**
     * Send a message to a contact
     *
     * @param string $to The contact's phone number
     * @param string $message The message to send
     * @return array Response from the API
     */
    public function sendMessage(string $to, string $message): array
    {
        try {
            // Format phone number (remove any + prefix if present)
            $to = ltrim($to, '+');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/{$this->phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('WhatsApp API error when sending message: ' . $response->body());
            return [];
            
        } catch (\Exception $e) {
            Log::error('WhatsApp API exception when sending message: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get messages for a specific contact
     *
     * @param string $contactNumber The contact's phone number
     * @return array Array of messages
     */
    public function getMessages(string $contactNumber): array
    {
        try {
            // Format phone number (remove any + prefix if present)
            $contactNumber = ltrim($contactNumber, '+');
            
            // In a real implementation, you would make an API call to fetch messages
            // This is a placeholder for the actual API call
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/{$this->phoneId}/messages", [
                'phone_number' => $contactNumber,
            ]);
            
            if ($response->successful()) {
                $messages = $this->formatMessages($response->json('data', []));
                return $messages;
            }
            
            Log::error('WhatsApp API error: ' . $response->body());
            
            // Return empty array if API call fails
            return $this->getFallbackMessages();
            
        } catch (\Exception $e) {
            Log::error('WhatsApp API exception: ' . $e->getMessage());
            
            // Return fallback messages if there's an exception
            return $this->getFallbackMessages();
        }
    }
    
    /**
     * Format messages from the API response to the format expected by the view
     *
     * @param array $apiMessages Messages from the API
     * @return array Formatted messages
     */
    protected function formatMessages(array $apiMessages): array
    {
        $formattedMessages = [];
        
        foreach ($apiMessages as $message) {
            $formattedMessages[] = [
                'type' => $message['from'] === $this->phoneId ? 'system' : 'user',
                'message' => $message['text']['body'] ?? '',
                'time' => \Carbon\Carbon::parse($message['timestamp'])->format('H:i'),
            ];
        }
        
        return $formattedMessages;
    }
    
    /**
     * Get fallback messages in case the API call fails
     *
     * @return array Fallback messages
     */
    protected function getFallbackMessages(): array
    {
        return [
            [
                'type' => 'system',
                'message' => 'No se pudieron cargar los mensajes de WhatsApp. Por favor, intenta de nuevo más tarde.',
                'time' => now()->format('H:i'),
            ]
        ];
    }
}
