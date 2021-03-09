<?php

declare(strict_types=1);

namespace App\Controller\Mencho;

use App\Entity\MenchoMantra;
use App\Service\Mencho\Exception\InvalidUuidException;
use App\Service\Mencho\MenchoMantraService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class MenchoMantraParamResolver implements ArgumentValueResolverInterface
{
    private const PARAM_MANTRA_UUID = 'mantra_uuid';

    public function __construct(
        private MenchoMantraService $menchoMantraService
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return MenchoMantra::class === $argument->getType() &&
            null !== $request->get(self::PARAM_MANTRA_UUID);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $paramMantraUuid = $request->get(self::PARAM_MANTRA_UUID);
        if (!(Uuid::isValid($paramMantraUuid))) {
            throw new InvalidUuidException();
        }
        yield $this->menchoMantraService->findByUuid(Uuid::fromString($paramMantraUuid));
    }
}
