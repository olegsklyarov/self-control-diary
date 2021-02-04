<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Entity\User;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiSamayaPostCest
{
    public function testSuccessPost(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya success');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $I->grabService('security.password_encoder');
        $user = new User('user@example.com');
        $user->setPassword(
            $userPasswordEncoder->encodePassword($user, 'my-strong-password')
        );
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($diary);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/mencho/samaya', [
            'notedAt' => '2021-02-04',
            'mantraUuid' => $mantraBuddhaShakyamuni->getUuid(),
            'count' => 100,
            'timeMinutes' => 15,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.uuid');
        $I->seeResponseJsonMatchesJsonPath('$.count');
        $I->seeResponseJsonMatchesJsonPath('$.timeMinutes');

        $actualResponseUuid = $I->grabDataFromResponseByJsonPath('$.uuid')[0];
        $actualResponseMantraUuid = $I->grabDataFromResponseByJsonPath('$.menchoMantra.uuid')[0];
        $actualResponseCount = $I->grabDataFromResponseByJsonPath('$.count')[0];
        $actualResponseTimeMinutes = $I->grabDataFromResponseByJsonPath('$.timeMinutes')[0];

        $I->assertEquals($mantraBuddhaShakyamuni->getUuid(), $actualResponseMantraUuid);
        $I->assertEquals(100, $actualResponseCount);
        $I->assertEquals(15, $actualResponseTimeMinutes);

        $I->seeInRepository(MenchoSamaya::class, ['uuid' => $actualResponseUuid]);

        /** @var MenchoSamaya $menchoSamayaInRepository */
        $menchoSamayaInRepository = $I->grabEntitiesFromRepository(
            MenchoSamaya::class,
            ['uuid' => $actualResponseUuid]
        )[0];

        $I->assertEquals($actualResponseUuid, $menchoSamayaInRepository->getUuid());
        $I->assertEquals(100, $menchoSamayaInRepository->getCount());
        $I->assertEquals(15, $menchoSamayaInRepository->getTimeMinutes());
        $I->assertEquals($mantraBuddhaShakyamuni->getUuid(), $menchoSamayaInRepository->getMenchoMantra()->getUuid());
    }

    public function testDiaryNotFound(ApiTester $I): void
    {
        $I->wantToTest('POST /api/mencho/samaya diary not found');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $I->grabService('security.password_encoder');
        $user = new User('user@example.com');
        $user->setPassword(
            $userPasswordEncoder->encodePassword($user, 'my-strong-password')
        );
        $I->haveInRepository($user);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);
        $I->sendPOST('/api/mencho/samaya', [
            'notedAt' => '2021-02-04',
            'mantraUuid' => $mantraBuddhaShakyamuni->getUuid(),
            'count' => 100,
            'timeMinutes' => 15,
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);

        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Diary not found.',
        ]);
    }
}
