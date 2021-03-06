<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Entity\Running;
use App\Entity\Sleeping;
use App\Entity\User;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiDiaryDeleteCest
{
    public function testSuccessDelete(ApiTester $I): void
    {
        $I->wantToTest('DELETE /api/diary/{noted_at} (cascade)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-01-30'));
        $I->haveInRepository($diary);

        $running = new Running($diary, 4700, 32, -11);
        $I->haveInRepository($running);

        $sleeping = new Sleeping($diary);
        $I->haveInRepository($sleeping);

        $menchoSamaya = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($menchoSamaya);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendDelete('/api/diary/2021-01-30');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

        $I->dontSeeInRepository(Diary::class, ['uuid' => $diary->getUuid()]);
        $I->dontSeeInRepository(MenchoSamaya::class, ['uuid' => $menchoSamaya->getUuid()]);
        $I->dontSeeInRepository(Sleeping::class, ['diary' => $sleeping->getDiary()]);
        $I->dontSeeInRepository(Running::class, ['diary' => $running->getDiary()]);

        $I->seeInRepository(MenchoMantra::class, ['uuid' => $mantraBuddhaShakyamuni->getUuid()]);
        $I->seeInRepository(User::class, ['uuid' => $user->getUuid()]);
    }

    public function testDiaryNotFound(ApiTester $I): void
    {
        $I->wantToTest('DELETE /api/diary/{noted_at} (diary not found)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendDelete('/api/diary/2021-01-30');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function testNotAuthorized(ApiTester $I): void
    {
        $I->wantToTest('DELETE /api/diary/{noted_at} (unauthorized)');
        $I->sendDelete('/api/diary/2021-01-30');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function testBadNotedAt(ApiTester $I): void
    {
        $I->wantToTest('DELETE /api/diary/{noted_at} (bad noted_at)');
        $user = $I->createUser();
        $I->haveInRepository($user);
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendDelete('/api/diary/2021-Jan-30');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Invalid noted_at value.',
        ]);
    }
}
