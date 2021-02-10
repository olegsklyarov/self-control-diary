<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\User;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiMenchoSamayaPatchCest
{
    public function testSuccessPatch(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/mencho/samaya success');

        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $I->grabService('security.password_encoder');
        $user = new User('user@example.com');
        $user->setPassword(
            $userPasswordEncoder->encodePassword($user, 'my-strong-password')
        );
        $I->haveInRepository($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $I->haveInRepository($mantraBuddhaShakyamuni);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-10'));
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
            'notedAt' => '2021-02-10',
            'mantraUuid' => $mantraBuddhaShakyamuni->getUuid(),
            'count' => 100,
        ]);
        $I->sendPatch('/api/mencho/samaya', [
            'notedAt' => '2021-02-10',
            'mantraUuid' => $mantraBuddhaShakyamuni->getUuid(),
            'count' => 200,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
