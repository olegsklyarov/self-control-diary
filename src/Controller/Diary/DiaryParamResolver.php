<?php

declare(strict_types=1);

namespace App\Controller\Diary;

use App\Entity\Diary;
use App\Service\Diary\DiaryService;
use App\Service\Util;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class DiaryParamResolver implements ArgumentValueResolverInterface
{
    private const PARAM_NOTED_AT = 'noted_at';

    public function __construct(private DiaryService $diaryService)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return Diary::class === $argument->getType() && null !== $request->get(self::PARAM_NOTED_AT);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $paramNotedAt = $request->get(self::PARAM_NOTED_AT);

        yield Util::isValidDateFormat($paramNotedAt)
            ? $this->diaryService->findByNotedAtForCurrentUser(new \DateTimeImmutable($paramNotedAt))
            : null;
    }
}
