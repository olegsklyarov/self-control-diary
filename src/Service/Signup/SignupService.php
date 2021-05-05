<?php

declare(strict_types=1);

namespace App\Service\Signup;

use App\Controller\Signup\SignupDTO;
use App\Entity\Lead;
use App\Entity\User;
use App\Service\Signup\Exception\InvalidEmailException;
use App\Service\Signup\Exception\LeadIsAlreadyExistException;
use App\Service\Signup\Exception\UserIsAlreadyExistException;
use Doctrine\ORM\EntityManagerInterface;

final class SignupService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function persistFromDto(SignupDTO $signupDTO): Lead
    {
        $passwordHash = password_hash($signupDTO->password, PASSWORD_DEFAULT);
        $this->entityManager->getConnection()->beginTransaction();
        try {
            if (!filter_var($signupDTO->email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidEmailException();
            }
            if ($this->isUserExist($signupDTO->email)) {
                throw new UserIsAlreadyExistException();
            }
            if ($this->isLeadExistAndNotVerified($signupDTO->email)) {
                throw new LeadIsAlreadyExistException();
            }
            $createdLead = new Lead($signupDTO->email, $passwordHash);
            $this->entityManager->persist($createdLead);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $createdLead;
    }

    public function findUserByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);

        return $user;
    }

    public function findLeadByEmail(string $email): ?Lead
    {
        /** @var Lead|null $lead */
        $lead = $this->entityManager->getRepository(Lead::class)->findOneBy([
            'email' => $email,
        ]);

        return $lead;
    }

    private function isUserExist(string $email): bool
    {
        return null !== $this->findUserByEmail($email);
    }

    private function isLeadExistAndNotVerified(string $email): bool
    {
        $lead = $this->findLeadByEmail($email);
        if (null === $lead) {
            return false;
        }
        if (null === $lead->getVerifiedEmailAt()) {
            return true;
        }

        return false;
    }
}
