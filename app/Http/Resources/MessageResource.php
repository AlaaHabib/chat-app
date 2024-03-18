<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($message) {

            return [
                'id' => $message->id,
                'message' => $this->decrypt($message->message,$message->iv),
                'sender' => $message->sender,
            ];
        });
    }

    private function decrypt($message , $iv)
    {
        $key = config('app.key') ?? env('APP_KEY');
        $cipher = config('app.cipher'); // AES encryption with CBC mode
        $options = OPENSSL_RAW_DATA;

        // Decrypt the message using OpenSSL with AES encryption
        $decryptedMessage = openssl_decrypt($message, $cipher, $key, $options, $iv);

        return $decryptedMessage;
    }
}
