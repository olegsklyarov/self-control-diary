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

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'uuid' => 'string',
            'notes' => 'string|null',
            'notedAt' => 'string:date',
        ]);
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

    public function testMissedNotesSuccess(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary success with missed notes');

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
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'uuid' => 'string',
            'notes' => 'string|null',
            'notedAt' => 'string:date',
        ]);
        $I->seeResponseContainsJson([
            'notedAt' => '2021-02-21T00:00:00+00:00',
            'notes' => null,
        ]);

        $actualDiaryUuid = $I->grabDataFromResponseByJsonPath('$.uuid')[0];
        $I->seeInRepository(Diary::class, ['uuid' => $actualDiaryUuid]);

        /** @var Diary $diaryInRepository */
        $diaryInRepository = $I->grabEntitiesFromRepository(
            Diary::class,
            ['uuid' => $actualDiaryUuid]
        )[0];

        $I->assertEquals($actualDiaryUuid, $diaryInRepository->getUuid());
        $I->assertNull($diaryInRepository->getNotes());
        $I->assertEquals(new \DateTimeImmutable('2021-02-21'), $diaryInRepository->getNotedAt());
    }

    public function testNullNotesSuccess(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary success with null notes');

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
            'notes' => null,
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'uuid' => 'string',
            'notes' => 'string|null',
            'notedAt' => 'string:date',
        ]);
        $I->seeResponseContainsJson([
            'notedAt' => '2021-02-21T00:00:00+00:00',
            'notes' => null,
        ]);

        $actualDiaryUuid = $I->grabDataFromResponseByJsonPath('$.uuid')[0];
        $I->seeInRepository(Diary::class, ['uuid' => $actualDiaryUuid]);

        /** @var Diary $diaryInRepository */
        $diaryInRepository = $I->grabEntitiesFromRepository(
            Diary::class,
            ['uuid' => $actualDiaryUuid]
        )[0];

        $I->assertEquals($actualDiaryUuid, $diaryInRepository->getUuid());
        $I->assertNull($diaryInRepository->getNotes());
        $I->assertEquals(new \DateTimeImmutable('2021-02-21'), $diaryInRepository->getNotedAt());
    }

    public function testBlankNotesSuccess(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary success with blank notes');

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
            'notes' => '',
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'code' => 'integer',
            'message' => 'string',
        ]);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Validation errors: "notes" - This value should satisfy at least one of the following constraints: [1] This value is too short. It should have 1 character or more. [2] This value should be null.',
        ]);
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
            'message' => 'Validation errors: "notedAt" - This value should not be null., "notedAt" - This value should not be blank.',
        ]);
    }
}
