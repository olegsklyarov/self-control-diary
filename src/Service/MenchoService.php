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
    private MenchoSamayaService $menchoSamayaService;

    public function __construct(
        EntityManagerInterface $entityManager,
        DiaryService $diaryService,
        MenchoMantraService $menchoMantraService,
        MenchoSamayaService $menchoSamayaService
    ) {
        $this->entityManager = $entityManager;
        $this->diaryService = $diaryService;
        $this->menchoMantraService = $menchoMantraService;
        $this->menchoSamayaService = $menchoSamayaService;
    }

    public function getSamaya(Diary $diary): array
    {
        return $this->entityManager->getRepository(MenchoSamaya::class)->findByDiaryUuid($diary);
    }

    public function persistFromDto(MenchoSamayaDTO $menchoSamayaDTO): ?MenchoSamaya
    {
        $createdMenchoSamaya = null;
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $diary = $this->diaryService->findByNotedAtForCurrentUser(
                new \DateTimeImmutable($menchoSamayaDTO->notedAt)
            );
            if (null === $diary) {
                throw new MenchoServiceDiaryNotFoundException();
            }
            $menchoMantra = $this->menchoMantraService->findByUuid(
                Uuid::fromString($menchoSamayaDTO->mantraUuid)
            );
            if (null === $menchoMantra) {
                throw new MenchoServiceMantraNotFoundException();
            }
            if (null !== $this->menchoSamayaService->findByDiaryAndMantra($diary, $menchoMantra)) {
                throw new MenchoServiceExceptionAlreadyExists();
            }
            $createdMenchoSamaya = new MenchoSamaya(
                $diary,
                $menchoMantra,
                $menchoSamayaDTO->count
            );
            $createdMenchoSamaya->setTimeMinutes($menchoSamayaDTO->timeMinutes);
            $this->entityManager->persist($createdMenchoSamaya);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $createdMenchoSamaya;
    }
}
