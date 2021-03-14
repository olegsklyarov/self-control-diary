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
        $I->wantToTest('GET /api/mencho/samaya (success)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantra1 = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantra1);
        $mantra2 = new MenchoMantra('Амитаба', 1);
        $I->haveInRepository($mantra2);
        $mantra3 = new MenchoMantra('Ченрезиг', 1);
        $I->haveInRepository($mantra3);

        $diary1 = new Diary($user, new \DateTimeImmutable('2021-03-12'));
        $I->haveInRepository($diary1);
        $diary2 = new Diary($user, new \DateTimeImmutable('2021-03-13'));
        $I->haveInRepository($diary2);
        $diary3 = new Diary($user, new \DateTimeImmutable('2021-03-14'));
        $I->haveInRepository($diary3);

        $I->haveInRepository(new MenchoSamaya($diary1, $mantra1, 100));
        $I->haveInRepository(new MenchoSamaya($diary1, $mantra2, 100));
        $I->haveInRepository(new MenchoSamaya($diary1, $mantra3, 100));
        $I->haveInRepository(new MenchoSamaya($diary2, $mantra1, 100));
        $I->haveInRepository(new MenchoSamaya($diary2, $mantra2, 200));
        $I->haveInRepository(new MenchoSamaya($diary2, $mantra3, 300));
        $I->haveInRepository(new MenchoSamaya($diary3, $mantra2, 700));

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/mencho/samaya');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                'mantraUuid' => $mantra1->getUuid()->toString(),
                'count' => 200,
            ],
            [
                'mantraUuid' => $mantra2->getUuid()->toString(),
                'count' => 1000,
            ],
            [
                'mantraUuid' => $mantra3->getUuid()->toString(),
                'count' => 400,
            ],
        ]);
    }

    public function testCrossUser(ApiTester $I): void
    {
        $I->wantToTest('GET /api/mencho/samaya (cross user test)');

        $user1 = $I->createUser();
        $I->haveInRepository($user1);

        $user2 = $I->createUser('user2@example.com', 'another-strong-password');
        $I->haveInRepository($user2);

        $mantra = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantra);

        $diary1 = new Diary($user1, new \DateTimeImmutable('2021-03-14'));
        $I->haveInRepository($diary1);
        $diary2 = new Diary($user2, new \DateTimeImmutable('2021-03-14'));
        $I->haveInRepository($diary2);

        $I->haveInRepository(new MenchoSamaya($diary1, $mantra, 100));
        $I->haveInRepository(new MenchoSamaya($diary2, $mantra, 700));

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/mencho/samaya');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                'mantraUuid' => $mantra->getUuid()->toString(),
                'count' => 100,
            ],
        ]);

        $token = $I->doAuthAndGetJwtToken($I, 'user2@example.com', 'another-strong-password');
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/mencho/samaya');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                'mantraUuid' => $mantra->getUuid()->toString(),
                'count' => 700,
            ],
        ]);
    }
}
