<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiDiaryPostCest
{
    public function testSuccessPost(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary success');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
            'notes' => 'My diary note',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.uuid');
        $I->seeResponseJsonMatchesJsonPath('$.notes');
        $I->seeResponseJsonMatchesJsonPath('$.notedAt');

        $I->seeResponseContainsJson([
            'notes' => 'My diary note',
            'notedAt' => '2021-02-21T00:00:00+00:00',
        ]);

        $actualDiaryUuid = $I->grabDataFromResponseByJsonPath('$.uuid')[0];
        $I->seeInRepository(Diary::class, ['uuid' => $actualDiaryUuid]);

        /** @var Diary $diaryInRepository */
        $diaryInRepository = $I->grabEntitiesFromRepository(
            Diary::class,
            ['uuid' => $actualDiaryUuid]
        )[0];

        $I->assertEquals($actualDiaryUuid, $diaryInRepository->getUuid());
        $I->assertEquals('My diary note', $diaryInRepository->getNotes());
        $I->assertEquals(new \DateTimeImmutable('2021-02-21'), $diaryInRepository->getNotedAt());
    }

    public function testConflictPost(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary conflict');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-21'));
        $I->haveInRepository($diary);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
            'notes' => 'My diary note',
        ]);

        $I->seeResponseCodeIs(HttpCode::CONFLICT);
        $I->seeResponseContainsJson([
            'code' => HttpCode::CONFLICT,
            'message' => 'Diary already exists',
        ]);
    }

    public function testNotAuthorized(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary unauthorized');
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
            'notes' => 'My diary note',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function testBadRequest(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary bad request');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary');

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Validation errors: "notes" - This value should not be blank., "notedAt" - This value should not be null., "notedAt" - This value should not be blank.',
        ]);
    }
}
