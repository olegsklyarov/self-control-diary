<?php

declare(strict_types=1);

namespace App\Service\Mencho;

use App\Entity\MenchoMantra;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class MenchoMantraService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
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
