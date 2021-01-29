<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Entity\Running;
use App\Entity\Sleeping;
use App\Entity\User;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiDiaryDeleteCest
{
    public function testSuccessDelete(ApiTester $I): void
    {
        $I->wantToTest('DELETE /api/diary/{noted_at} (cascade)');

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $I->grabService('security.password_encoder');
        $user = new User('user@example.com');
        $user->setPassword(
            $userPasswordEncoder->encodePassword($user, 'my-strong-password')
        );
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-01-30'));
        $I->haveInRepository($diary);

        $running = new Running($diary, 4700, 32, -11);
        $I->haveInRepository($running);

        $sleeping = new Sleeping($diary);
        $I->haveInRepository($sleeping);

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
        $I->sendDelete('/api/diary/2021-01-30');
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR);
    }
}
