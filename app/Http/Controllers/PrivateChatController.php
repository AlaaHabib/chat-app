<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Http\Requests\PrivateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Http\Responses\Response;
use App\Repositories\MessageRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class PrivateChatController extends Controller
{
    public MessageRepositoryEloquent $messageRepository;

    public function __construct(MessageRepositoryEloquent $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }
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
        $data = [
            'message' => $encryptedMessage,
            'receiver_id' => $receiverId,
            'sender_id' => Auth::user()->id,
            'iv' => $iv,
        ];
        $this->messageRepository->create($data);
        // Using Redis
        // Emit the message to the user's room
        // Redis::publish("private-chat-room-{$receiverId}", json_encode(['message' => $encryptedMessage]));

        return Response::create()
            ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1011]))
            ->setStatusCode(StatusCode::HTTP_OK)
            ->setResponseCode(AppConstants::APP_1011)
            ->success();
    }

        /**
     * @OA\Get(
     *     path="/api/v1/receiver-messages",
     *     summary="Get all own messages",
     *     tags={"Chat"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit the number of results",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *    @OA\Parameter(
     *          name="page",
     *          description="page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */
    public function receiveMessage(Request $request)
    {
        $limit = $request->query('limit', null);
        $query = $this->messageRepository->searchByUser(Auth::user()->id);
 
        // Paginate the results
        $result = $query->orderBy('created_at', 'asc')->paginate($limit);

        $result = new MessageResource($result);
        $result = $result->response()->getData(true);

        return Response::create()
            ->setData($result)
            ->setStatusCode(StatusCode::HTTP_OK)
            ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1012]))
            ->setResponseCode(AppConstants::APP_1012)
            ->success();
    }
}
