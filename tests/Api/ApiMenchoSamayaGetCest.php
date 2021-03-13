<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiMenchoSamayaGetCest
{
    private ?MenchoSamaya $menchoSamayaShakyamuni = null;
    private ?MenchoSamaya $menchoSamayaChenrezig = null;

    public function _before(ApiTester $I): void
    {
        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $mantraChenrezig = new MenchoMantra('Ченрезиг', 1);
        $I->haveInRepository($mantraChenrezig);

        $diary = new Diary($user, new \DateTimeImmutable('2021-03-05'));
        $I->haveInRepository($diary);

        $this->menchoSamayaShakyamuni = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($this->menchoSamayaShakyamuni);

        $this->menchoSamayaChenrezig = new MenchoSamaya($diary, $mantraChenrezig, 300);
        $I->haveInRepository($this->menchoSamayaChenrezig);
    }

    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('GET /api/mencho/{noted_at} (success)');

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/mencho/2021-03-05');
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            [
                'uuid' => $this->menchoSamayaShakyamuni->getUuid()->toString(),
                'count' => 100,
            ],
            [
                'uuid' => $this->menchoSamayaChenrezig->getUuid()->toString(),
                'count' => 300,
            ],
        ]);
    }

    public function testDairyNotFound(ApiTester $I): void
    {
        $I->wantToTest('GET /api/mencho/{noted_at} (dairy not found)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/mencho/2021-03-08');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => HttpCode::NOT_FOUND,
            'message' => 'Diary not found.',
        ]);
    }

    public function testUnauthorised(ApiTester $I): void
    {
        $I->wantToTest('GET /api/mencho/{noted_at} (unauthorized)');
        $I->sendGet('/api/mencho/2021-03-05');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
