<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiToken;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com/v18.0');
        $this->apiToken = config('services.whatsapp.api_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
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
            $response = Http::withToken($this->apiToken)
                ->get("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
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
                'type' => $message['from'] === $this->phoneNumberId ? 'system' : 'user',
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
    
    /**
     * Send a message to a contact
     *
     * @param string $contactNumber The contact's phone number
     * @param string $message The message to send
     * @return bool Whether the message was sent successfully
     */
    public function sendMessage(string $contactNumber, string $message): bool
    {
        try {
            // Format phone number (remove any + prefix if present)
            $contactNumber = ltrim($contactNumber, '+');
            
            $response = Http::withToken($this->apiToken)
                ->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $contactNumber,
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]);
            
            if ($response->successful()) {
                return true;
            }
            
            Log::error('WhatsApp API error when sending message: ' . $response->body());
            return false;
            
        } catch (\Exception $e) {
            Log::error('WhatsApp API exception when sending message: ' . $e->getMessage());
            return false;
        }
    }
}
