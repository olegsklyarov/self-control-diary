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

    private function isDiaryExists(\DateTimeInterface $notedAt): bool
    {
        return null !== $this->findByNotedAtForCurrentUser($notedAt);
    }

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function findByNotedAtForCurrentUser(\DateTimeInterface $notedAt): ?Diary
    {
        /** @var Diary|null $diary */
        $diary = $this->entityManager->getRepository(Diary::class)->findOneBy([
            'notedAt' => $notedAt,
            'user' => $this->getCurrentUser(),
        ]);

        return $diary;
    }

    public function persistFromDto(DiaryDTO $diaryDTO): ?Diary
    {
        $notedAt = new \DateTimeImmutable($diaryDTO->notedAt);
        $createdDiary = null;
        $this->entityManager->transactional(function(EntityManagerInterface $em) use ($notedAt, $diaryDTO, &$createdDiary) {
            if ($this->isDiaryExists($notedAt)) {
                return;
            }
            $createdDiary = new Diary($this->getCurrentUser(), $notedAt);
            $createdDiary->setNotes($diaryDTO->notes);
            $em->persist($createdDiary);
        });

        return $createdDiary;
    }

}
