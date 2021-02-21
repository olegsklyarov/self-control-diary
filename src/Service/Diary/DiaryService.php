<?php

declare(strict_types=1);

namespace App\Service\Diary;

use App\Controller\Diary\DiaryDTO;
use App\Entity\Diary;
use App\Entity\User;
use App\Service\Diary\Exception\DiaryAlreadyExistsException;
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

    /**
     * @throws DiaryAlreadyExistsException
     */
    public function persistFromDto(DiaryDTO $diaryDTO): Diary
    {
        $notedAt = new \DateTimeImmutable($diaryDTO->notedAt);
        $this->entityManager->getConnection()->beginTransaction();
        try {
            if ($this->isDiaryExists($notedAt)) {
                throw new DiaryAlreadyExistsException();
            }
            $createdDiary = new Diary($this->getCurrentUser(), $notedAt);
            $createdDiary->setNotes($diaryDTO->notes);
            $this->entityManager->persist($createdDiary);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $createdDiary;
    }

    public function updateFromDTO(DiaryDTO $diaryDTO): ?Diary
    {
        $notedAt = new \DateTimeImmutable($diaryDTO->notedAt);
        $diary = $this->findByNotedAtForCurrentUser($notedAt);
        if (!$diary) {
            return null;
        }
        $diary->setNotes($diaryDTO->notes);
        $this->entityManager->persist($diary);
        $this->entityManager->flush();

        return $diary;
    }

    public function delete(Diary $diary): void
    {
        $this->entityManager->remove($diary);
        $this->entityManager->flush();
    }
}
