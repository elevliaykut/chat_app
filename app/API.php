<?php namespace App;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class API
 * @package App
 */
class API
{
    private $success;

    private $status;

    private $message;

    private $errorMessage;

    private $errorCode;

    private $additionalData;

    private function __construct($success, $status)
    {
        $this->success = (boolean)$success;
        $this->status  = $status;
    }

    /**
     * Success Response
     *
     * @param int $status
     *
     * @return static
     */
    public static function success(int $status = 200): self
    {
        return new static(true, $status);
    }

    /**
     * Fail Response
     *
     * @param int $status
     *
     * @return static
     */
    public static function error(int $status = 400): self
    {
        return new static(false, $status);
    }

    /**
     * Warning Response
     *
     * @param int $status
     *
     * @return static
     */
    public static function warning(int $status = 400): self
    {
        return new static(false, $status);
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function message(string $message = null): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string|null $errorMessage
     * @return $this
     */
    public function errorMessage(string $errorMessage = null): self
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @param int $errorCode
     * @return $this
     */
    public function errorCode(int $errorCode = 0): self
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @param $additionalData
     * @return $this
     */
    public function additionalData($additionalData = 0): self
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function response($data = null): JsonResponse
    {
        if ($data instanceof AnonymousResourceCollection) {
            $responseData = [
                'success'    => $this->success,
                'status'     => $this->status,
                'message'    => $this->message ?? '',
                'error_code' => $this->errorCode ?? 0,
                'additional' => $data->additional,
            ];

            if (!empty($this->additionalData) && getType($this->additionalData) === 'array') {
                $responseData += $this->additionalData;
            }
            if (!empty($this->additionalData) && getType($this->additionalData) !== 'array') {
                $responseData[ 'additionalData' ] = $this->additionalData;
            }
            return $data->additional($responseData)->response();
        }

        $response = [
            'success'    => $this->success,
            'status'     => $this->status,
            'message'    => $this->success ? $this->message : $this->errorMessage,
            'error_code' => $this->errorCode ?? 0,
            'data'       => $data,
        ];

        if (!empty($this->additionalData) && getType($this->additionalData) === 'array') {
            //$response[ 'additionalData' ] = $this->additionalData;
            $response += $this->additionalData;
        }

        return response()->json($response, $this->status);
    }
}
