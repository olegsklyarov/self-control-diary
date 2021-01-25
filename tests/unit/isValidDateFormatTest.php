<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\Util;

class isValidDateFormatTest extends \Codeception\Test\Unit
{
    public function testIsValidDateFormat()
    {
        self::assertTrue(Util::isValidDateFormat('2021-01-26'));
        self::assertFalse(Util::isValidDateFormat(''));
        self::assertFalse(Util::isValidDateFormat('57079b9d-47da-4071-802f-d32b35068ae5'));
    }
}
