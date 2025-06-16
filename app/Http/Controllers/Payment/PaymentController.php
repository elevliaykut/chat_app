<?php

namespace App\Http\Controllers\Payment;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Resources\Payment\PaymentListResource;
use App\Models\Payment\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * @var PaymentService
     */
    protected PaymentService $paymentService;

    /**
     * Undocumented function
     *
     * @param PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * @param CreatePaymentRequest $createPaymentRequest
     * @return JsonResponse
     */
    public function store(CreatePaymentRequest $createPaymentRequest): JsonResponse
    {
        $validatedData = $createPaymentRequest->validated();

        $userId = auth()->user()->id;

        $validatedData['user_id'] = $userId;
        
        $payment = $this->paymentService->create($validatedData);

        return API::success()->response(PaymentListResource::make($payment));
    }
    
    /**
     * @param integer $id
     * @return JsonResponse
     */
    public function paymentComplete(int $id): JsonResponse
    {
        $payment = $this->paymentService->retrieveById($id);

        $data = [
            'completed'         => true
        ];

        $payment->update($data);

        return API::success()->response();
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return API::success()->response(PaymentListResource::collection(Payment::all()));
    }
}
