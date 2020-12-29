<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\DiaryDTO;
use App\Entity\Diary;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

final class DiaryService
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    private function getCurrentUser(): User
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user;
    }

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function findByDateAndCurrentUser(\DateTimeImmutable $notedAt): ?Diary
    {
        return $this->entityManager->getRepository(Diary::class)->findOneBy([
            'notedAt' => $notedAt,
            'user' => $this->getCurrentUser(),
        ]);
    }

    public function isDiaryExistsByDto(DiaryDTO $diaryDTO): bool
    {
        return null !== $this->findByDateAndCurrentUser($diaryDTO->getNotedAt());
    }

    public function persistFromDto(DiaryDTO $diaryDTO): Diary
    {
        $diary = new Diary(
            $this->getCurrentUser(),
            $diaryDTO->getNotedAt(),
        );
        $diary->setNotes($diaryDTO->getNotes());

        $this->entityManager->persist($diary);
        $this->entityManager->flush();

        return $diary;
    }

}
