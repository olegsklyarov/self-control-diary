<?php

declare(strict_types=1);

namespace App\Service\Mencho;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use Doctrine\ORM\EntityManagerInterface;

final class MenchoSamayaService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByDiaryAndMantra(Diary $diary, MenchoMantra $menchoMantra): ?MenchoSamaya
    {
        /** @var MenchoSamaya|null $menchoSamaya */
        $menchoSamaya = $this->entityManager->getRepository(MenchoSamaya::class)->findOneBy([
            'diary' => $diary,
            'menchoMantra' => $menchoMantra,
        ]);

        return $menchoSamaya;
    }
}
