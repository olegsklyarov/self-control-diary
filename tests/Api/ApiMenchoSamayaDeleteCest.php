<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiMenchoSamayaDeleteCest
{
    public function testSuccessDelete(ApiTester $I): void
    {
        $I->wantToTest('DELETE /api/mencho/samaya/{noted_at} (success)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);
        $mantraChenrezig = new MenchoMantra('Ченрезиг', 1);
        $I->haveInRepository($mantraChenrezig);

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-18'));
        $I->haveInRepository($diary);

        $menchoSamayaBuddaShakyamuni = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($menchoSamayaBuddaShakyamuni);
        $menchoSamayaChenrezig = new MenchoSamaya($diary, $mantraChenrezig, 200);
        $I->haveInRepository($menchoSamayaChenrezig);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendDelete('/api/mencho/samaya/2021-02-18');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $I->dontSeeInRepository(MenchoSamaya::class, ['uuid' => $menchoSamayaBuddaShakyamuni->getUuid()]);
        $I->dontSeeInRepository(MenchoSamaya::class, ['uuid' => $menchoSamayaChenrezig->getUuid()]);
    }

    public function testDiaryNotFound(ApiTester $I): void
    {
        $I->wantToTest('DELETE /api/mencho/samaya/{noted_at} (diary not found)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $user = $I->createUser();
        $I->haveInRepository($user);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendDelete('/api/mencho/samaya/2021-02-18');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
