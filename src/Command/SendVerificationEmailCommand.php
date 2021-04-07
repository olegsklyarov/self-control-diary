<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Lead\LeadService;
use App\Service\Signup\EmailNotificationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SendVerificationEmailCommand extends Command
{
    protected static $defaultName = 'signup:send-verification-email';

    // TODO replace with real verification url
    private const DEFAULT_VERIFICATION_LINK = 'https://ya.ru';

    public function __construct(
        private EmailNotificationInterface $emailNotification,
        private LeadService $leadService,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setDescription('Send email using SendPulse service to verify user email');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $leadsToNotify = $this->leadService->findLeadsForVerificationEmail();
            foreach ($leadsToNotify as $lead) {
                $output->writeln(sprintf('Sending verification URL to %s...', $lead->getEmail()));
                $this->emailNotification->sendVerificationEmail($lead->getEmail(), null, self::DEFAULT_VERIFICATION_LINK);
                $lead->setVerificationEmailSentAt(new \DateTimeImmutable());
                $this->entityManager->persist($lead);
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        $output->writeln(empty($leadsToNotify)
            ? 'No leads waiting for verification URL.'
            : 'Done.'
        );

        return 0;
    }
}
