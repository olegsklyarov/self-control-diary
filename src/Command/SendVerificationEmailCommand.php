<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\EmailNotificationInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SendVerificationEmailCommand extends Command
{
    protected static $defaultName = 'signup:send-verification-email';

    public function __construct(private EmailNotificationInterface $emailNotification)
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setDescription('Send email using SendPulse service to verify user email');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        echo 'Sending verification email...' . PHP_EOL;

        $this->emailNotification->sendVerificationEmail('oleg.sklyarov@gmail.com', null, 'https://ya.ru');

        echo 'DONE' . PHP_EOL;

        return 0;
    }
}
