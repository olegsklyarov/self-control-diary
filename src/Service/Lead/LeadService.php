<?php

declare(strict_types=1);

namespace App\Service\Lead;

use App\Entity\Lead;
use Doctrine\ORM\EntityManagerInterface;

final class LeadService
{
    public const MAX_LEADS_TO_SEND_EMAIL = 10;

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @return Lead[]
     */
    public function findLeadsForVerificationEmail(): array
    {
        return $this->entityManager->getRepository(Lead::class)->findBy(
            [
                'verifiedEmailAt' => null,
                'verificationEmailSentAt' => null,
            ],
            null,
            self::MAX_LEADS_TO_SEND_EMAIL
        );
    }
}
