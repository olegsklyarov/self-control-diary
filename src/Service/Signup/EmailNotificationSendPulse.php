<?php

declare(strict_types=1);

namespace App\Service\Signup;

use Sendpulse\RestApi\ApiClient as SendpulseApiClient;

final class EmailNotificationSendPulse implements EmailNotificationInterface
{
    private const SEND_PULSE_EVENT_EMAIL_VERIFICATION = 'email_verification';

    private SendpulseApiClient $sendpulseApiClient;

    public function __construct(string $apiUserId, string $apiSecret)
    {
        $this->sendpulseApiClient = new SendpulseApiClient($apiUserId, $apiSecret);
    }

    public function sendVerificationEmail(string $email, ?string $phone, string $verificationUrl): void
    {
        $this->sendpulseApiClient->startEventAutomation360(self::SEND_PULSE_EVENT_EMAIL_VERIFICATION, [
            'email' => $email,
            'phone' => $phone,
            'verification_url' => $verificationUrl,
        ]);
    }
}
