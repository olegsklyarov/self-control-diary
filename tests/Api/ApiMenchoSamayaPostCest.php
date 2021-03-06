<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiMenchoSamayaPostCest
{
    public function testSuccessPost(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya (success)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($diary);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/mencho/samaya', [
            'notedAt' => '2021-02-04',
            'mantraUuid' => $mantraBuddhaShakyamuni->getUuid(),
            'count' => 100,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.uuid');
        $I->seeResponseJsonMatchesJsonPath('$.count');

        $I->seeResponseContainsJson([
            'count' => 100,
            'menchoMantra' => [
                'uuid' => $mantraBuddhaShakyamuni->getUuid()->toString(),
            ],
        ]);

        $actualResponseUuid = $I->grabDataFromResponseByJsonPath('$.uuid')[0];
        $I->seeInRepository(MenchoSamaya::class, ['uuid' => $actualResponseUuid]);

        /** @var MenchoSamaya $menchoSamayaInRepository */
        $menchoSamayaInRepository = $I->grabEntitiesFromRepository(
            MenchoSamaya::class,
            ['uuid' => $actualResponseUuid]
        )[0];

        $I->assertEquals($actualResponseUuid, $menchoSamayaInRepository->getUuid());
        $I->assertEquals(100, $menchoSamayaInRepository->getCount());
        $I->assertEquals($mantraBuddhaShakyamuni->getUuid(), $menchoSamayaInRepository->getMenchoMantra()->getUuid());
    }

    public function testAlreadyExists(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya (already exists)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-05'));
        $I->haveInRepository($diary);

        $menchoSamaya = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $I->haveInRepository($menchoSamaya);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/mencho/samaya', [
            'notedAt' => '2021-02-05',
            'mantraUuid' => $mantraBuddhaShakyamuni->getUuid(),
            'count' => 100,
        ]);

        $I->seeResponseCodeIs(HttpCode::CONFLICT);
        $I->seeResponseContainsJson([
            'code' => HttpCode::CONFLICT,
            'message' => 'Already exists.',
        ]);
    }

    public function testDiaryNotFound(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya (diary not found)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $user = $I->createUser();
        $I->haveInRepository($user);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/mencho/samaya', [
            'notedAt' => '2021-02-04',
            'mantraUuid' => $mantraBuddhaShakyamuni->getUuid(),
            'count' => 100,
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);

        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Diary not found.',
        ]);
    }

    public function testMenchoMantraNotFound(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya (mantra not found)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($diary);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/mencho/samaya', [
            'notedAt' => '2021-02-04',
            'mantraUuid' => 'f0df115c-ae46-4510-9552-f89fde1f48be',
            'count' => 100,
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Mantra not found.',
        ]);
    }

    public function testMenchoMantraInvalidUuid(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya (mantra uuid invalid)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($diary);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/mencho/samaya', [
            'notedAt' => '2021-02-04',
            'mantraUuid' => 'invalid-uuid',
            'count' => 100,
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Validation errors: "mantraUuid" - This is not a valid UUID.',
        ]);
    }

    public function testDataValidationFailed(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya (data validation failed)');

        $user = $I->createUser();
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($diary);

        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/mencho/samaya', [
            'notedAt' => 0,
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'The type of the "notedAt" attribute for class "App\Controller\Mencho\MenchoSamayaDTO" must be one of "string" ("int" given).',
        ]);
    }
}
