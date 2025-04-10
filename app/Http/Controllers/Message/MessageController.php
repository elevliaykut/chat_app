<?php

namespace App\Http\Controllers\Message;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message\Message;
use App\Services\Message\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected MessageService $messageService;

    /**
     * UserController constructor.
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Undocumented function
     *
     * @param SendMessageRequest $sendMessageRequest
     * @return JsonResponse
     */
    public function sendMessage(SendMessageRequest $sendMessageRequest): JsonResponse
    {
        $validatedData = $sendMessageRequest->validated();

        $validatedData['sender_id'] = auth()->user()->id;

        $message = $this->messageService->create($validatedData);

        return API::success()->response(MessageResource::make($message));
    }

    /**
     * Undocumented function
     *
     * @param integer $userId
     * @return JsonResponse
     */
    public function getMessages(int $userId): JsonResponse
    {
        $authId = auth()->id();

        $messages = Message::where(function($query) use ($authId, $userId) {
            $query->where('sender_id', $authId)->where('receiver_id', $userId);
        })->orWhere(function($query) use ($authId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $authId);
        })->orderBy('created_at', 'asc')->get();

        return API::success()->response(MessageResource::collection($messages));
    }
}
