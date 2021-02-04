<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MenchoMantra;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class MenchoMantraService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByUuid(UuidInterface $uuid): ?MenchoMantra
    {
        /** @var MenchoMantra|null $menchoMantra */
        $menchoMantra = $this->entityManager
            ->getRepository(MenchoMantra::class)
            ->findOneBy([
                'uuid' => $uuid,
            ]);

        return $menchoMantra;
    }
}
