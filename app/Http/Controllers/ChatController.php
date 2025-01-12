<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\ChatGPTService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatGPTService;

    public function __construct(ChatGPTService $chatGPTService)
    {
        $this->chatGPTService = $chatGPTService;
    }

    public function chat(Request $request)
    {
        $userMessage = $request->input('message');
        $sessionId = $request->input('session_id') ?? session()->getId();
        $previousMessages = Conversation::where('session_id', $sessionId)
            ->get(['user_message', 'bot_response'])
            ->map(function ($item) {
                return ['role' => 'assistant', 'content' => $item->bot_response];
            })->toArray();

        $messages = array_merge($previousMessages, [['role' => 'user', 'content' => $userMessage]]);
        $response = $this->chatGPTService->sendMessage($messages);
        $botResponse = $response['choices'][0]['message']['content'];

        Conversation::create([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'bot_response' => $botResponse,
        ]);

        return response()->json(['message' => $botResponse]);
    }
}
