<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\User;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiDocGetCest
{
    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('open page /api/doc (success)');
        $user = $I->createUser();
        $user->addRole(User::ROLE_ADMIN);
        $I->haveInRepository($user);
        $I->amHttpAuthenticated('user@example.com', 'my-strong-password');
        $I->amOnPage('/api/doc');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInTitle('SCD REST API');
    }

    public function testNotAdmin(ApiTester $I): void
    {
        $I->wantToTest('open page /api/doc (not admin user)');
        $user = $I->createUser();
        $I->haveInRepository($user);
        $I->amHttpAuthenticated('user@example.com', 'my-strong-password');
        $I->amOnPage('/api/doc');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function testUnauthorised(ApiTester $I): void
    {
        $I->wantToTest('open page /api/doc (unauthorised)');
        $I->amOnPage('/api/doc');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
