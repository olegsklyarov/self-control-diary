<?php

declare(strict_types=1);

namespace App\Controller\Diary;

use App\Exception\HttpRequestSelfValidationException;
use App\Exception\HttpRequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class DiaryInputDTOParamResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return DiaryInputDTO::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        try {
            /** @var DiaryInputDTO $diaryInputDTO */
            $diaryInputDTO = $this->serializer->deserialize(
                $request->getContent(),
                DiaryInputDTO::class,
                JsonEncoder::FORMAT,
            );
        } catch (\Throwable $e) {
            throw new BadRequestHttpException(null, $e, $e->getCode());
        }

        $selfViolations = $diaryInputDTO->validate();
        if (count($selfViolations) > 0) {
            throw new HttpRequestSelfValidationException($selfViolations);
        }

        $violations = $this->validator->validate($diaryInputDTO);
        if (count($violations) > 0) {
            throw new HttpRequestValidationException($violations);
        }

        yield $diaryInputDTO;
    }
}
