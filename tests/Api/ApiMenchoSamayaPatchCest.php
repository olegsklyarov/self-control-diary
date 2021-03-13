<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiMenchoSamayaPatchCest
{
    public function testSuccessPatch(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/mencho/samaya (success)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-10'));
        $I->haveInRepository($diary);

        $menchoSamaya = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($menchoSamaya);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);

        $I->sendPatch('/api/mencho/samaya', [
            'notedAt' => '2021-02-10',
            'mantraUuid' => $mantraBuddhaShakyamuni->getUuid(),
            'count' => 200,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseJsonMatchesJsonPath('$.uuid');
        $I->seeResponseJsonMatchesJsonPath('$.count');

        $I->seeResponseContainsJson([
            'uuid' => $menchoSamaya->getUuid()->toString(),
            'count' => 200,
        ]);

        $I->seeInRepository(MenchoSamaya::class, ['uuid' => $menchoSamaya->getUuid()]);

        /** @var MenchoSamaya $menchoSamayaInRepository */
        $menchoSamayaInRepository = $I->grabEntitiesFromRepository(
            MenchoSamaya::class,
            ['uuid' => $menchoSamaya->getUuid()]
        )[0];

        $I->assertEquals($menchoSamaya->getUuid()->toString(), $menchoSamayaInRepository->getUuid());
        $I->assertEquals(200, $menchoSamayaInRepository->getCount());
        $I->assertEquals($mantraBuddhaShakyamuni->getUuid(), $menchoSamayaInRepository->getMenchoMantra()->getUuid());
    }
}
