<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiMenchoTotalSamayaGetCest
{
    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('GET /api/mecnho/samaya/total (success)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $mantraChenrezig = new MenchoMantra('Ченрезиг', 1);
        $I->haveInRepository($mantraChenrezig);

        $diary20210312 = new Diary($user, new \DateTimeImmutable('2021-03-12'));
        $I->haveInRepository($diary20210312);

        $diary20210313 = new Diary($user, new \DateTimeImmutable('2021-03-13'));
        $I->haveInRepository($diary20210313);

        $I->haveInRepository(new MenchoSamaya($diary20210312, $mantraBuddhaShakyamuni, 100));
        $I->haveInRepository(new MenchoSamaya($diary20210312, $mantraChenrezig, 700));

        $I->haveInRepository(new MenchoSamaya($diary20210313, $mantraBuddhaShakyamuni, 400));

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/mencho/samaya');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                'mantraUuid' => $mantraBuddhaShakyamuni->getUuid()->toString(),
                'count' => 500,
            ],
            [
                'mantraUuid' => $mantraChenrezig->getUuid()->toString(),
                'count' => 700,
            ],
        ]);
    }
}
