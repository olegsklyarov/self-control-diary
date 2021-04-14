<?php

declare(strict_types=1);

namespace App\Service\Signup;

use App\Controller\Signup\SignupDTO;
use App\Entity\Lead;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Security;

final class SignupService
{
    public function __construct(private EntityManagerInterface $entityManager, private Security $security)
    {
    }

    public function persistFromDto(SignupDTO $signupDTO): Lead
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $createdLead = new Lead();
            $createdLead->setUuid(Uuid::uuid4())
                ->setEmail($signupDTO->email)
                ->setPasswordHash(password_hash($signupDTO->password, PASSWORD_DEFAULT));
            $this->entityManager->persist($createdLead);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $createdLead;
    }

    public function findByEmail(string $email): ?Lead
    {
        /** @var Lead|null $lead */
        $lead = $this->entityManager->getRepository(Lead::class)->findOneBy([
            'email' => $email,
        ]);

        return $lead;
    }
}
