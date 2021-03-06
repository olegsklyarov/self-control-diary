<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

final class Util
{
    public static function isValidDateFormat(string $date): bool
    {
        if (preg_match("/(\d{4})-(\d{2})-(\d{2})/", $date, $matches)) {
            return checkdate((int) $matches[2], (int) $matches[3], (int) $matches[1]);
        }

        return false;
    }

    public static function errorJsonResponse(int $code, string $message): JsonResponse
    {
        return new JsonResponse([
            'code' => $code,
            'message' => $message,
        ], $code);
    }
}
