<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\MenchoSamayaDTO;
use App\Entity\Diary;
use App\Entity\MenchoSamaya;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final class MenchoService
{
    private EntityManagerInterface $entityManager;
    private DiaryService $diaryService;
    private MenchoMantraService $menchoMantraService;

    public function __construct(
        EntityManagerInterface $entityManager,
        DiaryService $diaryService,
        MenchoMantraService $menchoMantraService
    ) {
        $this->entityManager = $entityManager;
        $this->diaryService = $diaryService;
        $this->menchoMantraService = $menchoMantraService;
    }

    public function getSamaya(Diary $diary): array
    {
        return $this->entityManager->getRepository(MenchoSamaya::class)->findByDiaryUuid($diary);
    }

    public function persistFromDto(MenchoSamayaDTO $menchoSamayaDTO): ?MenchoSamaya
    {
        $diaryService = $this->diaryService;
        $menchoMantraService = $this->menchoMantraService;
        $createdMenchoSamaya = null;
        $this->entityManager->transactional(function (EntityManagerInterface $em) use ($menchoSamayaDTO, $diaryService, $menchoMantraService, &$createdMenchoSamaya): void {
            $diary = $diaryService->findByNotedAtForCurrentUser(
                new \DateTimeImmutable($menchoSamayaDTO->notedAt)
            );
            if (null === $diary) {
                return;
            }
            $menchoMantra = $menchoMantraService->findByUuid(
                Uuid::fromString($menchoSamayaDTO->mantraUuid)
            );
            if (null === $menchoMantra) {
                return;
            }
            $createdMenchoSamaya = new MenchoSamaya(
                $diary,
                $menchoMantra,
                $menchoSamayaDTO->count
            );
            $createdMenchoSamaya->setTimeMinutes($menchoSamayaDTO->timeMinutes);
            $em->persist($createdMenchoSamaya);
            $em->flush();
        });

        return $createdMenchoSamaya;
    }
}
