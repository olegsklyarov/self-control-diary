<?php

declare(strict_types=1);

namespace App\Controller\Mencho;

use App\Entity\MenchoSamaya;
use App\Service\Diary\DiaryService;
use App\Service\Mencho\MenchoMantraService;
use App\Service\Mencho\MenchoSamayaService;
use App\Service\Util;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class MenchoSamayaParamResolver implements ArgumentValueResolverInterface
{
    private const PARAM_NOTED_AT = 'noted_at';
    private const PARAM_MANTRA_UUID = 'mantra_uuid';

    private DiaryService $diaryService;
    private MenchoSamayaService $menchoSamayaService;
    private MenchoMantraService $menchoMantraService;

    public function __construct(
        DiaryService $diaryService,
        MenchoSamayaService $menchoSamayaService,
        MenchoMantraService $menchoMantraService
    ) {
        $this->diaryService = $diaryService;
        $this->menchoSamayaService = $menchoSamayaService;
        $this->menchoMantraService = $menchoMantraService;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return MenchoSamaya::class === $argument->getType() &&
            null !== $request->get(self::PARAM_NOTED_AT) &&
            null !== $request->get(self::PARAM_MANTRA_UUID);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $paramNotedAt = $request->get(self::PARAM_NOTED_AT);
        $paramMantraUuid = $request->get(self::PARAM_MANTRA_UUID);

        if (Util::isValidDateFormat($paramNotedAt)) {
            $diary = $this->diaryService->findByNotedAtForCurrentUser(new \DateTimeImmutable($paramNotedAt));
            $mantra = $this->menchoMantraService->findByUuid(Uuid::fromString($paramMantraUuid));
            yield $this->menchoSamayaService->findByDiaryAndMantra($diary, $mantra);
        }

        yield null;
    }
}
