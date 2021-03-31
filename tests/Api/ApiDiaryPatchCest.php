<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiDiaryPathCest
{
    private ?Diary $diary = null;

    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/diary (success)');
        $user = $I->createUser();
        $I->haveInRepository($user);
        $diary = new Diary($user, new \DateTimeImmutable('2021-03-21'));
        $I->haveInRepository($diary);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPatch('/api/diary', [
            'notedAt' => '2021-03-21',
            'notes' => 'My diary note',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'uuid' => $diary->getUuid()->toString(),
            'notes' => 'My diary note',
            'notedAt' => $diary->getNotedAt()->format(\DateTime::ATOM),
        ]);
        $I->seeInRepository(Diary::class, ['uuid' => $diary->getUuid()]);

        /** @var Diary $diaryInRepository */
        $diaryInRepository = $I->grabEntitiesFromRepository(
            Diary::class,
            ['uuid' => $diary->getUuid()]
        )[0];

        $I->assertEquals($diary->getUuid()->toString(), $diaryInRepository->getUuid());
        $I->assertEquals('My diary note', $diaryInRepository->getNotes());
        $I->assertEquals('2021-03-21T00:00:00+00:00', $diaryInRepository->getNotedAt()->format(\DateTime::ATOM));
    }

    public function testNotAuthorized(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/diary (unautorized)');
        $user = $I->createUser();
        $I->haveInRepository($user);
        $diary = new Diary($user, new \DateTimeImmutable('2021-03-21'));
        $I->haveInRepository($diary);
        $I->sendPatch('/api/diary', [
            'notedAt' => '2021-03-21',
            'notes' => 'My diary new note',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function testDairyNotFound(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/diary (404 dairy not found)');
        $user = $I->createUser();
        $I->haveInRepository($user);
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPatch('/api/diary', [
            'notedAt' => '2031-03-21',
            'notes' => 'My diary new note',
        ]);

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
