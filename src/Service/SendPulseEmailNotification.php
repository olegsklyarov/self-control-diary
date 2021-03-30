<?php

declare(strict_types=1);

namespace App\Service;

use Sendpulse\RestApi\ApiClient;

final class SendPulseEmailNotification implements EmailNotificationInterface
{
    private const API_USER_ID = '1f631511c945123ff2ccf024c19c4653';
    private const API_SECRET = 'b50b4ed18a70baeb77bc1c07b02ffdef';

    public function sendVerificationEmail(string $email, ?string $phone, string $verificationUrl): void
    {
        $apiClient = new ApiClient(self::API_USER_ID, self::API_SECRET);



    }
}
