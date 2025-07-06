<?php

namespace App\Http\Controllers;

use App\Models\Whatsapp;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function getMessages(Request $request)
    {
        $contactNumber = $request->contactNumber;
        $whatsapp = Whatsapp::where('numero', $contactNumber)->first();
        
        if ($whatsapp) {
            return response()->json([
                'messages' => $whatsapp->mensajes,
                'success' => true
            ]);
        }
        
        return response()->json([
            'messages' => [],
            'success' => false
        ]);
    }
}
