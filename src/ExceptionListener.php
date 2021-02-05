<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ExceptionListener
{
    public function onKernelException(ExceptionEvent $exceptionEvent): void
    {
        $exception = $exceptionEvent->getThrowable();
        $response = new JsonResponse();
        $data = ['message' => $exception->getMessage()];

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $data['code'] = $exception->getStatusCode();
            $response->headers->replace($exception->getHeaders());
        }

        $response->setData($data);
        $exceptionEvent->setResponse($response);
    }
}
