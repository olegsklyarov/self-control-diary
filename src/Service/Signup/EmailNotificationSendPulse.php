<?php

declare(strict_types=1);

namespace App\Service\Signup;

use Sendpulse\RestApi\ApiClient as SendpulseApiClient;

final class EmailNotificationSendPulse implements EmailNotificationInterface
{
    private const SEND_PULSE_EVENT_EMAIL_VERIFICATION = 'email_verification';

    public function __construct(
        private string $apiUserId,
        private string $apiSecret,
    ) {
    }

    public function sendVerificationEmail(string $email, ?string $phone, string $verificationUrl): void
    {
        $client = new SendpulseApiClient($this->apiUserId, $this->apiSecret);
        $client->startEventAutomation360(self::SEND_PULSE_EVENT_EMAIL_VERIFICATION, [
            'email' => $email,
            'phone' => $phone,
            'verification_url' => $verificationUrl,
        ]);
    }
}
