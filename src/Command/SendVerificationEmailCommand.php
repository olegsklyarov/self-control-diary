<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\EmailNotificationInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SendVerificationEmailCommand extends Command
{
    protected static $defaultName = 'signup:send-verification-email';

    private const DEFAULT_VERIFICATION_LINK = 'https://ya.ru';

    private const OPTION_EMAIL = 'email';
    private const OPTION_PHONE = 'phone';

    public function __construct(private EmailNotificationInterface $emailNotification)
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->setDescription('Send email using SendPulse service to verify user email')
            ->addOption(
                self::OPTION_EMAIL,
                null,
                InputOption::VALUE_REQUIRED,
                'email address to send verification link',
                null
            )
            ->addOption(
                self::OPTION_PHONE,
                null,
                InputOption::VALUE_OPTIONAL,
                'phone number of the user',
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getOption(self::OPTION_EMAIL);
        if (null === $email) {
            $output->writeln('please specify email parameter');

            return 0;
        }

        $phone = $input->getOption(self::OPTION_PHONE);
        $output->writeln(sprintf('Sending verification link to email %s...', $email));
        $this->emailNotification->sendVerificationEmail($email, $phone, self::DEFAULT_VERIFICATION_LINK);
        $output->writeln('DONE');

        return 0;
    }
}
