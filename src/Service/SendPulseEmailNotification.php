<?php

declare(strict_types=1);

namespace App\Service;

use Sendpulse\RestApi\ApiClient as SendpulseApiClient;

final class SendPulseEmailNotification implements EmailNotificationInterface
{
    private const SEND_PULSE_EVENT_EMAIL_VERIFICATION = 'email_verification';

    private SendpulseApiClient $sendpulseApiClient;

    public function __construct(
        private string $apiUserId,
        private string $apiSecret,
    ) {
        $this->sendpulseApiClient = new SendpulseApiClient($this->apiUserId, $this->apiSecret);
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
