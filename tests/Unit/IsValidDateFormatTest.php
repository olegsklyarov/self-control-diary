<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Service\Util;
use Codeception\Test\Unit;

class IsValidDateFormatTest extends Unit
{
    public function testValidDateFormat(): void
    {
        self::assertTrue(Util::isValidDateFormat('2021-01-26'));
    }

    /**
     * @dataProvider invalidDateFormatProvider
     */
    public function testInvalidDateFormat(string $invalidDate): void
    {
        self::assertFalse(Util::isValidDateFormat($invalidDate));
    }

    public function invalidDateFormatProvider(): array
    {
        return [
            ['20210126'],
            ['26.01.2021'],
            ['57079b9d-47da-4071-802f-d32b35068ae5'],
        ];
    }
}
