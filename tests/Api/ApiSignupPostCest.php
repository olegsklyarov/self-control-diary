<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiSignupPostCest
{
    public function testSuccessPost(ApiTester $I): void
    {
        $I->wantToTest('POST /api/signup (success)');
        $I->sendPOST('/api/signup', [
            'email' => 'signup@gmail.com',
            'password' => 'userStrongPassword',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'code' => HttpCode::OK,
            'message' => 'Please check your email inbox and follow verification link',
        ]);
    }
}
