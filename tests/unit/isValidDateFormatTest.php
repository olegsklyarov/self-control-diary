<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\Util;

class isValidDateFormatTest extends \Codeception\Test\Unit
{
    public function testIsValidDateFormat()
    {
        self::assertTrue(Util::isValidDateFormat('2021-01-26'));
    }
}
