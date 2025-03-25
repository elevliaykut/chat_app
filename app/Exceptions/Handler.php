<?php

namespace App\Exceptions;

use App\API;
use DivisionByZeroError;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use TypeError;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->renderable(function (AuthorizationException $e, $request) {
            return API::error(401)->errorMessage('AuthenticationException')->response('Unauthorized');
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            return API::error(401)->errorMessage('AuthenticationException')->response('Unauthorized');
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return API::error()->errorMessage(__('Object Requested NOT FOUND') . ' ' . last(explode("\\", $e->getMessage())))->response();
        });

        $this->renderable(function (\RuntimeException $e, $request) {
            return API::error()->errorMessage($e->getMessage())->response();
        });

        $this->renderable(function (ValidationException $e, $request) {
            $errors = collect($e->validator->errors()->toArray());

            $errors->transform(function ($errors) {
                return collect($errors)->first();
            });

            return API::error(422)->errorMessage($errors->first())->response($errors);
        });

        $this->renderable(function (BaseException $e, $request) {
            return API::error($e->getCode())->errorMessage($e->getMessage())->response();
        });

        $this->renderable(function (DivisionByZeroError $e, $request) {
            return API::error($e->getCode())->errorMessage($e->getMessage())->response();
        });

        $this->renderable(function (TypeError $e, $request) {
            return API::error(422)->errorMessage($e->getMessage())->response();
        });

        if (!config('app.debug')) {
            $this->renderable(function (Throwable $e, $request) {
                return API::error(400)->errorMessage(
                    sprintf(
                        '%s (%s: %s)',
                        __('exception.server_error'),
                        __('exception.error_code'),
                        $e->getCode()
                    )
                )->response();
            });
        }

        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e) && app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }
}
