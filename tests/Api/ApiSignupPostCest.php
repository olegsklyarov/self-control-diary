<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Lead;
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
            'message' => 'Please check your email inbox and follow verification link.',
        ]);
    }

    public function testInvalidEmail(ApiTester $I): void
    {
        $I->wantToTest('POST /api/signup (invalid email)');
        $I->sendPOST('/api/signup', [
            'email' => 'hello, world',
            'password' => 'userStrongPassword',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Please specify correct email address.',
        ]);
    }

    public function testEmailAlreadySignedUp(ApiTester $I): void
    {
        $I->wantToTest('POST /api/signup (email already signed up)');

        $user = $I->createUser();
        $user->setEmail('signup@gmail.com');
        $I->haveInRepository($user);

        $I->sendPOST('/api/signup', [
            'email' => 'signup@gmail.com',
            'password' => 'userStrongPassword',
        ]);
        $I->seeResponseCodeIs(HttpCode::CONFLICT);
        $I->seeResponseContainsJson([
            'code' => HttpCode::CONFLICT,
            'message' => 'Such email already signed up.',
        ]);
    }

    public function testVerificationEmailExist(ApiTester $I): void
    {
        $I->wantToTest('POST /api/signup (verification email already exist)');

        $lead = new Lead();
        $lead->setEmail('signup@gmail.com')->setVerifiedEmailAt(null);
        $I->haveInRepository($lead);

        $I->sendPOST('/api/signup', [
            'email' => 'signup@gmail.com',
            'password' => 'userStrongPassword',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseContainsJson([
            'code' => HttpCode::FORBIDDEN,
            'message' => 'Please check your email inbox and follow verification link.',
        ]);
    }
}
