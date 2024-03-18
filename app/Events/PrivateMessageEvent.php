<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PrivateMessageEvent implements ShouldBroadcastNow
{
    public $senderId;
    public $encryptedMessage;
    public $receiverId;
    public $iv;

    public function __construct($senderId, $encryptedMessage, $receiverId, $iv)
    {
        $this->senderId = $senderId;
        $this->encryptedMessage = $encryptedMessage;
        $this->receiverId = $receiverId;
        $this->iv = $iv;

    }

    public function broadcastOn()
    {
        return new PrivateChannel('private-chat-room-' . $this->receiverId);
    }

    public function broadcastWith()
    {
        return ['message' => $this->encryptedMessage];
    }
    // If want to decrypt message 

    // public function decryptMessage()
    // {
    //     $key = config('app.key') ?? env('APP_KEY');
    //     $cipher = config('app.cipher'); // AES encryption with CBC mode
    //     $options = OPENSSL_RAW_DATA;

    //     // Decrypt the message using OpenSSL with AES encryption
    //     $decryptedMessage = openssl_decrypt($this->encryptedMessage, $cipher, $key, $options, $this->iv);

    //     return $decryptedMessage;
    // }
    public function broadcastAs()
    {
        return 'private-message';
    }
}
