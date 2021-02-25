<?php

declare(strict_types=1);

namespace App\Controller\Mencho;

use App\Exception\HttpRequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class MenchoSamayaDTOParamResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return MenchoSamayaDTO::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        try {
            /** @var MenchoSamayaDTO $menchoSamayaDTO */
            $menchoSamayaDTO = $this->serializer->deserialize(
                $request->getContent(),
                MenchoSamayaDTO::class,
                JsonEncoder::FORMAT
            );
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, $e->getCode());
        }

        $violations = $this->validator->validate($menchoSamayaDTO);
        if (count($violations) > 0) {
            throw new HttpRequestValidationException($violations);
        }

        yield $menchoSamayaDTO;
    }
}
