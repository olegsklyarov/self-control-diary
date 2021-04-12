<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Signup\SendVerificationUrlService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SendVerificationEmailCommand extends Command
{
    protected static $defaultName = 'signup:send-verification-email';

    public function __construct(
        private SendVerificationUrlService $sendVerificationUrlService,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setDescription('Send email using SendPulse service to verify user email');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sendVerificationUrlService->findAndNotifyLeadsWaitingForVerificationUrl();

        return 0;
    }
}
