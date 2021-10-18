<?php

declare(strict_types=1);

namespace App\Service\Signup;

use App\Service\Lead\LeadService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final class SendVerificationUrlService
{
    private const VERIFICATION_URL_BASE = 'http://self-control-diary:8080/signup/verify/';
    private LeadService $leadService;
    private EntityManagerInterface $entityManager;
    private EmailNotificationInterface $emailNotification;

    public function __construct(
        LeadService $leadService,
        EntityManagerInterface $entityManager,
        EmailNotificationInterface $emailNotification
    ) {
        $this->leadService = $leadService;
        $this->entityManager = $entityManager;
        $this->emailNotification = $emailNotification;
    }

    public function findAndNotifyLeadsWaitingForVerificationUrl(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $leadsToNotify = $this->leadService->findLeadsForVerificationEmail();
            foreach ($leadsToNotify as $lead) {
                $lead->setVerificationToken(Uuid::uuid4()->toString());
                $this->emailNotification->sendVerificationEmail(
                    $lead->getEmail(),
                    null,
                    self::VERIFICATION_URL_BASE . $lead->getVerificationToken(),
                );
                $lead->setVerificationEmailSentAt(new \DateTimeImmutable());
                $this->entityManager->persist($lead);
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }
}
