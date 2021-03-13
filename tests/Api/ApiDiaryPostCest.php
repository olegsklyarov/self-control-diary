<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiDiaryPostCest
{
    private function checkSuccessResponse(ApiTester $I, ?string $expectedNotes, \DateTimeImmutable $expectedNotedAt): void
    {
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'uuid' => 'string',
            'notes' => 'string|null',
            'notedAt' => 'string:date',
        ]);
        $I->seeResponseContainsJson([
            'notes' => $expectedNotes,
            'notedAt' => $expectedNotedAt->format(\DateTime::ATOM),
        ]);

        $actualDiaryUuid = $I->grabDataFromResponseByJsonPath('$.uuid')[0];
        $I->seeInRepository(Diary::class, ['uuid' => $actualDiaryUuid]);

        /** @var Diary $diaryInRepository */
        $diaryInRepository = $I->grabEntitiesFromRepository(
            Diary::class,
            ['uuid' => $actualDiaryUuid]
        )[0];

        $I->assertEquals($actualDiaryUuid, $diaryInRepository->getUuid());
        $I->assertEquals($expectedNotes, $diaryInRepository->getNotes());
        $I->assertEquals($expectedNotedAt, $diaryInRepository->getNotedAt());
    }

    private function checkBadRequestResponse(ApiTester $I, int $expectedHttpCode, string $expectedMessage): void
    {
        $I->seeResponseCodeIs($expectedHttpCode);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'code' => 'integer',
            'message' => 'string',
        ]);
        $I->seeResponseContainsJson([
            'code' => $expectedHttpCode,
            'message' => $expectedMessage,
        ]);
    }

    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary (success)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
            'notes' => 'My diary note',
        ]);

        $this->checkSuccessResponse($I, 'My diary note', new \DateTimeImmutable('2021-02-21'));
    }

    public function testNullNotesSuccess(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary (with null notes)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
            'notes' => null,
        ]);

        $this->checkSuccessResponse($I, null, new \DateTimeImmutable('2021-02-21'));
    }

    public function testMissedNotes(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary (with missed notes)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
        ]);

        $this->checkBadRequestResponse(
            $I,
            HttpCode::BAD_REQUEST,
            'Self validation errors: "notes" - property should exists'
        );
    }

    public function testBlankNotesSuccess(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary (success with blank notes)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
            'notes' => '',
        ]);

        $this->checkBadRequestResponse(
            $I,
            HttpCode::BAD_REQUEST,
            'Validation errors: "notes" - This value should satisfy at least one of the following constraints: [1] This value is too short. It should have 1 character or more. [2] This value should be null.'
        );
    }

    public function testMissedNotedAt(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary (empty request body)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary', [
            'notes' => null,
        ]);

        $this->checkBadRequestResponse(
            $I,
            HttpCode::BAD_REQUEST,
            'Validation errors: "notedAt" - This value should not be null., "notedAt" - This value should not be blank.'
        );
    }

    public function testConflictPost(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary (conflict)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-21'));
        $I->haveInRepository($diary);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
            'notes' => 'My diary note',
        ]);

        $this->checkBadRequestResponse(
            $I,
            HttpCode::CONFLICT,
            'Diary already exists.'
        );
    }

    public function testNotAuthorized(ApiTester $I): void
    {
        $I->wantToTest('POST /api/diary (unauthorized)');
        $I->sendPOST('/api/diary', [
            'notedAt' => '2021-02-21',
            'notes' => 'My diary note',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
