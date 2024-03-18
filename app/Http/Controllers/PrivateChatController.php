<?php

namespace App\Http\Controllers;

use App\Events\PrivateMessageEvent;
use App\Http\Requests\PrivateMessageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class PrivateChatController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/send-message",
     *     summary="Send a message to user",
     *     tags={"Chat"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="receiver_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Message sent"
     *     )
     * )
     */
    public function sendMessage(PrivateMessageRequest $request)
    {
        $message = $request->input('message');
        $receiverId = $request->input('receiver_id');

        // Encrypt the message using AES encryption
        $key = config('app.key') ?? env('APP_KEY');
        $cipher = config('app.cipher');
        $options = OPENSSL_RAW_DATA;
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encryptedMessage = openssl_encrypt($message, $cipher, $key, $options, $iv);
        
        // Using Redis
        // Emit the message to the user's room
        Redis::publish("private-chat-room-{$receiverId}", json_encode(['message' => $encryptedMessage]));

        // For using laravel-echo uncomment broadcast line  
        // Broadcast the encrypted message and IV
        // broadcast(new PrivateMessageEvent($user->id, $encryptedMessage, $receiverId, $iv));

        return response()->json(['status' => 'Message sent']);
    }
}
