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
    private ?Diary $diary = null;
 
    public function testSuccessPatch(ApiTester $I): void
    {
        $I->wantToTest('GET /api/mencho/2021-03-05 success');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $diary = new Diary($user, new \DateTimeImmutable('2021-03-05'));
        $I->haveInRepository($diary);

        $menchoSamaya = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($menchoSamaya);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);

        $I->sendGet('/api/mencho/2021-03-05');
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            'uuid' => $menchoSamaya->getUuid()->toString(),
            'count' => 100,
        ]);

        $I->seeInRepository(MenchoSamaya::class, ['uuid' => $menchoSamaya->getUuid()]);

        /** @var MenchoSamaya $menchoSamayaInRepository */
        $menchoSamayaInRepository = $I->grabEntitiesFromRepository(
            MenchoSamaya::class,
            ['uuid' => $menchoSamaya->getUuid()]
        )[0];

        $I->assertEquals($menchoSamaya->getUuid()->toString(), $menchoSamayaInRepository->getUuid());
        $I->assertEquals(100, $menchoSamayaInRepository->getCount());
        $I->assertEquals($mantraBuddhaShakyamuni->getUuid(), $menchoSamayaInRepository->getMenchoMantra()->getUuid());
    }

    public function testDairyInvalidUuid(ApiTester $I): void
    {
        $I->wantToTest('GET /api/mencho/samaya/{noted at} (dairy not found)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($diary);

        $menchoSamaya = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($menchoSamaya);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/mencho/2021-03-08');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function testUnauthorised(ApiTester $I): void
    {
        $I->wantToTest('GET /api/mencho/samaya/{noted at} (Unauthorised)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $diary = new Diary($user, new \DateTimeImmutable('2021-03-05'));
        $I->haveInRepository($diary);

        $menchoSamaya = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($menchoSamaya);

        $I->sendGet('/api/mencho/2021-03-05');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
