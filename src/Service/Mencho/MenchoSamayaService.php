<?php

declare(strict_types=1);

namespace App\Service\Mencho;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

final class MenchoSamayaService
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
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

    public function findTotalSamayaForCurrentUser(): array
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select([
                'IDENTITY(samaya.menchoMantra) as mantraUuid',
                'SUM(samaya.count) as count',
            ])
            ->from(MenchoSamaya::class, 'samaya')
            ->innerJoin(Diary::class, 'diary', 'WITH', 'samaya.diary = diary')
            ->innerJoin(User::class, 'user', 'WITH', 'diary.user = user')
            ->andWhere('user = :user')
            ->setParameter('user', $user)
            ->groupBy('samaya.menchoMantra');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
