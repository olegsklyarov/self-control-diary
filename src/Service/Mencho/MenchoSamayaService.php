<?php

declare(strict_types=1);

namespace App\Service\Mencho;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Service\Mencho\Exception\DiaryNotFoundException;
use App\Service\Mencho\Exception\MantraNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

final class MenchoSamayaService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByDiaryAndMantra(?Diary $diary, ?MenchoMantra $menchoMantra): ?MenchoSamaya
    {
        $menchoSamaya = null;
        try {
            if (null === $diary) {
                throw new DiaryNotFoundException();
            }
            if (null === $menchoMantra) {
                throw new MantraNotFoundException();
            }
            /** @var MenchoSamaya|null $menchoSamaya */
            $menchoSamaya = $this->entityManager->getRepository(MenchoSamaya::class)->findOneBy([
                'diary' => $diary,
                'menchoMantra' => $menchoMantra,
            ]);
        } catch (\Throwable $e) {
        }

        return $menchoSamaya;
    }
}
