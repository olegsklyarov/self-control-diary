<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class HttpRequestValidationException extends BadRequestHttpException
{
    public function __construct(ConstraintViolationListInterface $violations, \Exception $previous = null)
    {
        parent::__construct(
            'Validation errors: ' . $this->violationsToString($violations),
            $previous,
            Response::HTTP_BAD_REQUEST
        );
    }

    private function violationsToString(ConstraintViolationListInterface $violations): string
    {
        return implode(', ', array_map(
            fn (ConstraintViolationInterface $violation): string => sprintf(
                '"%s" - %s',
                $violation->getPropertyPath(),
                $violation->getMessage()
            ), iterator_to_array($violations))
        );
    }
}
