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
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
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
                'IDENTITY(mencho_samaya.menchoMantra) as mantraUuid',
                'SUM(mencho_samaya.count) as count',
            ])
            ->from(MenchoSamaya::class, 'mencho_samaya')
            ->innerJoin(Diary::class, 'diary', 'WITH', 'mencho_samaya.diary = diary')
            ->innerJoin(User::class, 'u', 'WITH', 'diary.user = u')
            ->andWhere('u = :user')
            ->setParameter('user', $user)
            ->groupBy('mencho_samaya.menchoMantra');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
