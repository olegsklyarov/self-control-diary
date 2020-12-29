<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\HttpRequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class DiaryDTOParamResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === DiaryDTO::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        try {
            $diaryDTO = $this->serializer->deserialize(
                $request->getContent(),
                DiaryDTO::class,
                JsonEncoder::FORMAT,
            );
        } catch (\Throwable $e) {
            throw new BadRequestHttpException(null, $e, $e->getCode());
        }

        $violations = $this->validator->validate($diaryDTO);
        if (count($violations) > 0) {
            throw new HttpRequestValidationException($violations);
        }

        yield $diaryDTO;
    }
}
