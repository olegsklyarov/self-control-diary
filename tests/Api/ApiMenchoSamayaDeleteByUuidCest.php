<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiMenchoSamayaDeleteByUuidCest
{
    public function testSuccessDelete(ApiTester $I): void
    {
        $I->wantToTest('DELETE /api/mencho/samaya/{noted_at}/{mantra_uuid}');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-18'));
        $I->haveInRepository($diary);

        $menchoSamayaBuddaShakyamuni = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($menchoSamayaBuddaShakyamuni);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);
        $I->sendDelete('/api/mencho/samaya/2021-02-18/' . $mantraBuddhaShakyamuni->getUuid());
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $I->dontSeeInRepository(MenchoSamaya::class, ['uuid' => $menchoSamayaBuddaShakyamuni->getUuid()]);
    }
}
