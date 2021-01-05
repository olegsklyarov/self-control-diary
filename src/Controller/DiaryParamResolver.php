<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Diary;
use App\Service\DiaryService;
use App\Service\Util;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class DiaryParamResolver implements ArgumentValueResolverInterface
{
    private const PARAM_NOTED_AT = 'noted_at';

    private DiaryService $diaryService;

    public function __construct(DiaryService $diaryService)
    {
        $this->diaryService = $diaryService;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Diary::class && $request->get(self::PARAM_NOTED_AT) !== null;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $paramNotedAt = $request->get(self::PARAM_NOTED_AT);

        yield Util::isValidDateFormat($paramNotedAt)
            ? $this->diaryService->findByNotedAtForCurrentUser(new \DateTimeImmutable($paramNotedAt))
            : null;
    }
}
