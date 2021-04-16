<?php

declare(strict_types=1);

namespace App\Service\Signup;

interface EmailNotificationInterface
{
    public function sendVerificationEmail(string $email, ?string $phone, string $verificationUrl): void;
}
