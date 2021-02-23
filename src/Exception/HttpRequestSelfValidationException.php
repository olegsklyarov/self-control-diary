<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class HttpRequestSelfValidationException extends BadRequestHttpException
{
    public function __construct(array $violations, \Exception $previous = null)
    {
        parent::__construct(
            'Self validation errors: ' . $this->violationsToString($violations),
            $previous,
            Response::HTTP_BAD_REQUEST
        );
    }

    private function violationsToString(array $violations): string
    {
        return substr(array_reduce(
            array_keys($violations),
            fn ($carry, $property): string => sprintf(
                $carry . '"%s" - %s, ',
                $property,
                $violations[$property]
            ),
            ''
        ), 0, -2);
    }
}
