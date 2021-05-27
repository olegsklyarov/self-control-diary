<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiMenchoSamayaCollectionPostCest
{
    public function testSuccessWithoutDiary(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya/collection (success without diary)');
        $user = $I->createUser();
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);
        $mantraPadmasambava = new MenchoMantra('Падмасамбава/Гуру Ринпоче', 2);
        $I->haveInRepository($mantraPadmasambava);

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);

        $notedAt = (new \DateTimeImmutable('2021-05-27'))->format(DATE_ATOM);

        $I->sendPOST('/api/mencho/samaya/collection', [
            'noted_at' => $notedAt,
            'samaya' => [
                [
                    'uuid' => $mantraBuddhaShakyamuni->getUuid()->toString(),
                    'count' => 100,
                ],
                [
                    'uuid' => $mantraPadmasambava->getUuid()->toString(),
                    'count' => 200,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeInRepository(Diary::class, ['notedAt' => $notedAt]);

        $diaryInRepository = $I->grabEntitiesFromRepository(
            Diary::class,
            ['notedAt' => $notedAt],
        )[0];

        $menchoSamayaInRepository = $I->grabEntitiesFromRepository(MenchoMantra::class, ['diary' => $diaryInRepository]);

        $I->assertCount(2, $menchoSamayaInRepository);
    }
}
