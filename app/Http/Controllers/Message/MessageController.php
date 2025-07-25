<?php

namespace App\Http\Controllers\Message;

use App\API;
use App\Helper\Statuses\UserStatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Resources\Message\MessageLogResource;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message\Message;
use App\Models\Message\MessageLog;
use App\Services\Message\MessageLogService;
use App\Services\Message\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected MessageService $messageService;

    protected MessageLogService $messageLogService;

    protected int $defaultPerPage = 20;

    /**
     * UserController constructor.
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService, MessageLogService $messageLogService)
    {
        $this->messageService = $messageService;

        $this->messageLogService = $messageLogService;
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

        if(!$this->messageLogService->logExists($validatedData['sender_id'], $validatedData['receiver_id'])) {
            $data = [
                'sender_id'     => $validatedData['sender_id'],
                'receiver_id'   => $validatedData['receiver_id']
            ];

            $this->messageLogService->create($data);
        }

        $message = $this->messageService->create($validatedData);

        return API::success()->response(MessageResource::make($message));
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getIncomingMessageLogs(): JsonResponse
    {
        $authId = auth()->id();

        $messageLogs = MessageLog::where('receiver_id', $authId)
        ->whereHas('sender', function ($query) use ($authId) {
            $query->where('status', UserStatusHelper::USER_STATUS_ACTIVE) // sadece aktif kullanıcılar
                ->whereDoesntHave('blockers', function ($q) use ($authId) {
                    $q->where('blocker_id', $authId);
                });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($this->defaultPerPage);

        return API::success()->response(MessageLogResource::collection($messageLogs));
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getOutgoingMessageLogs(): JsonResponse
    {        
        $authId = auth()->id();
        
        $messageLogs = MessageLog::where('sender_id', $authId)
        ->whereHas('receiver', function ($query) use ($authId) {
            $query->where('status', UserStatusHelper::USER_STATUS_ACTIVE) // sadece aktif kullanıcılar
                ->whereDoesntHave('blockers', function ($q) use ($authId) {
                    $q->where('blocker_id', $authId);
                });
        })
            ->orderBy('created_at', 'desc')
            ->paginate($this->defaultPerPage);

        return API::success()->response(MessageLogResource::collection($messageLogs));  
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getMessages(int $userId)
    {
        $authId = auth()->user()->id;

        $messages = Message::where(function ($query) use ($authId, $userId) {
            $query->where('sender_id', $authId)
                  ->where('receiver_id', $userId);
        })
        ->orWhere(function ($query) use ($authId, $userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $authId);
        })
        ->orderBy('created_at', 'asc') // Eğer eskiden yeniye görmek istersen
        ->get();

        return API::success()->response(MessageResource::collection($messages));
    }

    /**
     * @param int $senderId
     * @return JsonResponse
     */
    public function deleteIncomingMessage(int $senderId)
    {
        $currentUserId = auth()->user()->id;
        
        Message::where('receiver_id', $currentUserId)
            ->where('sender_id', $senderId)
            ->delete();

        MessageLog::where('receiver_id', $currentUserId)
            ->where('sender_id', $senderId)
            ->delete();

        return API::success()->response();
    }

    /**
     * @param int $receiverId
     * @return JsonResponse
     */
    public function deleteOutGoingMessage(int $receiverId)
    {
        $currentUserId = auth()->user()->id;

        Message::where('sender_id', $currentUserId)
            ->where('receiver_id', $receiverId)
            ->delete();

        MessageLog::where('sender_id', $currentUserId)
            ->where('receiver_id', $receiverId)
            ->delete();

        return API::success()->response();
    }

    /**
     * @param int $receiverId
     * @return JsonResponse
     */
    public function readIncomingMessage(int $senderId): JsonResponse
    {
        $currentUserId = auth()->user()->id;

        Message::where('receiver_id', $currentUserId)
            ->where('sender_id', $senderId)
            ->where('is_that_read', false)
            ->update(['is_that_read'    => true]);

        return API::success()->response();
    }
}
