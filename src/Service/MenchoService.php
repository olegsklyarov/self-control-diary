<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Diary;
use App\Entity\MenchoSamaya;
use Doctrine\ORM\EntityManagerInterface;

final class MenchoService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getSamaya(Diary $diary): array
    {
        return $this->entityManager->getRepository(MenchoSamaya::class)->findByDiaryUuid($diary);
    }
}
