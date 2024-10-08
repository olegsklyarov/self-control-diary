<?php

declare(strict_types=1);

namespace App\Service\Mencho;

use App\Controller\Mencho\MenchoSamayaDTO;
use App\Entity\Diary;
use App\Entity\MenchoSamaya;
use App\Service\Diary\DiaryService;
use App\Service\Mencho\Exception\DiaryNotFoundException;
use App\Service\Mencho\Exception\MantraNotFoundException;
use App\Service\Mencho\Exception\MenchoSamayaAlreadyExistsException;
use App\Service\Mencho\Exception\MenchoSamayaNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final class MenchoService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DiaryService $diaryService,
        private MenchoMantraService $menchoMantraService,
        private MenchoSamayaService $menchoSamayaService,
    ) {
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
                throw new DiaryNotFoundException();
            }
            $menchoMantra = $this->menchoMantraService->findByUuid(
                Uuid::fromString($menchoSamayaDTO->mantraUuid)
            );
            if (null === $menchoMantra) {
                throw new MantraNotFoundException();
            }
            if (null !== $this->menchoSamayaService->findByDiaryAndMantra($diary, $menchoMantra)) {
                throw new MenchoSamayaAlreadyExistsException();
            }
            $createdMenchoSamaya = new MenchoSamaya(
                $diary,
                $menchoMantra,
                $menchoSamayaDTO->count,
            );
            $this->entityManager->persist($createdMenchoSamaya);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $createdMenchoSamaya;
    }

    public function updateFromDto(MenchoSamayaDTO $menchoSamayaDTO): ?MenchoSamaya
    {
        $updatedMenchoSamaya = null;
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $diary = $this->diaryService->findByNotedAtForCurrentUser(
                new \DateTimeImmutable($menchoSamayaDTO->notedAt)
            );
            if (null === $diary) {
                throw new DiaryNotFoundException();
            }
            $menchoMantra = $this->menchoMantraService->findByUuid(
                Uuid::fromString($menchoSamayaDTO->mantraUuid)
            );
            if (null === $menchoMantra) {
                throw new MantraNotFoundException();
            }
            $existingMenchoSamaya = $this->menchoSamayaService->findByDiaryAndMantra($diary, $menchoMantra);
            if (null === $existingMenchoSamaya) {
                throw new MenchoSamayaNotFoundException();
            }
            $existingMenchoSamaya->setCount($menchoSamayaDTO->count);

            $this->entityManager->persist($existingMenchoSamaya);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $existingMenchoSamaya;
    }

    public function deleteSamayaByDiary(Diary $diary): void
    {
        /** @var Diary[] $menchoMantras */
        $menchoMantras = $this->getSamaya($diary);
        foreach ($menchoMantras as $menchoMantra) {
            $this->entityManager->remove($menchoMantra);
        }
        $this->entityManager->flush();
    }

    public function deleteSamayaByUuid(MenchoSamaya $menchoSamaya): void
    {
        $this->entityManager->remove($menchoSamaya->getMenchoMantra());
        $this->entityManager->flush();
    }
}
