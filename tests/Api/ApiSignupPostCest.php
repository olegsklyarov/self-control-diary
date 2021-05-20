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
            'password' => 'userStrongPassword1',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'code' => HttpCode::OK,
            'message' => 'Please check your email inbox and follow verification link.',
        ]);
        /** @var Lead $leadInRepository */
        $leadInRepository = $I->grabEntitiesFromRepository(
            Lead::class,
            ['email' => 'signup@gmail.com']
        )[0];
        $I->seeInRepository(Lead::class, ['uuid' => $leadInRepository->getUuid()]);
    }

    public function testInvalidEmail(ApiTester $I): void
    {
        $I->wantToTest('POST /api/signup (invalid email)');
        $I->sendPOST('/api/signup', [
            'email' => 'hello, world',
            'password' => 'userStrongPassword1',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Validation errors: "email" - Please specify correct email address.',
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
            'password' => 'userStrongPassword1',
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

        $lead = new Lead('signup@gmail.com', password_hash('userStrongPassword1', PASSWORD_DEFAULT));
        $I->haveInRepository($lead);

        $I->sendPOST('/api/signup', [
            'email' => 'signup@gmail.com',
            'password' => 'userStrongPassword1',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseContainsJson([
            'code' => HttpCode::FORBIDDEN,
            'message' => 'Please check your email inbox and follow verification link.',
        ]);
    }

    public function testShortPassword(ApiTester $I): void
    {
        $I->wantToTest('POST /api/signup (easy password (short))');
        $I->sendPOST('/api/signup', [
            'email' => 'signup@gmail.com',
            'password' => '1234567',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Validation errors: "password" - Your password length must be at least 8, contain latin letters and digits.',
        ]);
    }

    public function testLongPassword(ApiTester $I): void
    {
        $I->wantToTest('POST /api/signup (easy password (long))');
        $I->sendPOST('/api/signup', [
            'email' => 'signup@gmail.com',
            'password' => 'FAbI7sBlQ8xZI2fV69J3ZxKLYWssDei8WtenvrxeisvS1BjxWCWqn22MN2jNlKP6JwHKqAF1CbTJzt8oPxZP5zZ9fXfnzb4e2fSpm',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Validation errors: "password" - Your password length must be at least 8, contain latin letters and digits.',
        ]);
    }

    public function testSpecialCharactersPassword(ApiTester $I): void
    {
        $I->wantToTest('POST /api/signup (easy password (special characters))');
        $I->sendPOST('/api/signup', [
            'email' => 'signup@gmail.com',
            'password' => '!@#$%^&*()',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Validation errors: "password" - Your password length must be at least 8, contain latin letters and digits.',
        ]);
    }
}
