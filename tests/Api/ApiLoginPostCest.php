<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\MenchoMantra;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

final class ApiLoginPostCest
{
    public function _before(ApiTester $I): void
    {
        $user = $I->createUser();
        $I->haveInRepository($user);
    }

    public function testSuccessAuth(ApiTester $I): void
    {
        $I->wantToTest('POST /api/login (valid credentials)');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.refresh_token');
    }

    public function testIncorrectPassword(ApiTester $I): void
    {
        $I->wantToTest('POST /api/login (invalid credentials)');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'random@example.com',
            'password' => 'random',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseJsonMatchesJsonPath('$.code');
        $I->seeResponseJsonMatchesJsonPath('$.message');

        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Invalid credentials.',
        ]);
    }

    public function testInvalidMethod(ApiTester $I): void
    {
        $I->wantToTest('POST /api/login (invalid method)');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/api/login');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED);
    }

    public function testInvalidBody(ApiTester $I): void
    {
        $I->wantToTest('POST /api/login (invalid body)');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function testExpiredToken(ApiTester $I): void
    {
        $I->wantToTest('POST /api/login (expired token)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.refresh_token');

        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];
        $I->amBearerAuthenticated($token);

        // set JWT_TOKEN_TTL=2 in .env.test file
        sleep(2);

        $I->sendGet('/api/mencho/mantra');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Expired JWT Token',
        ]);
    }

    public function testTokenRefresh(ApiTester $I): void
    {
        $I->wantToTest('POST /api/token/refresh (success)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.refresh_token');

        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];
        $refreshToken = $I->grabDataFromResponseByJsonPath('$.refresh_token')[0];

        $I->amBearerAuthenticated($token);

        // set JWT_TOKEN_TTL=2 in .env.test file
        sleep(2);

        $I->sendGet('/api/mencho/mantra');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Expired JWT Token',
        ]);

        $I->sendPOST('/api/token/refresh', [
            'refresh_token' => $refreshToken,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.refresh_token');

        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];
        $I->amBearerAuthenticated($token);

        $I->sendGet('/api/mencho/mantra');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
