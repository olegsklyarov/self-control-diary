<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Entity\User;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiDiaryGetCest
{
    private ?Diary $diary = null;

    public function _before(ApiTester $I): void
    {
        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $I->grabService('security.password_encoder');
        $user = new User('user@example.com');
        $user->setPassword(
            $userPasswordEncoder->encodePassword($user, 'my-strong-password')
        );
        $I->haveInRepository($user);

        $this->diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($this->diary);
    }

    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{noted_at} Success');

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);

        $I->sendGet('/api/diary/2021-02-04');
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseJsonMatchesJsonPath('$.uuid');
        $I->seeResponseJsonMatchesJsonPath('$.notes');
        $I->seeResponseJsonMatchesJsonPath('$.notedAt');

        $actualResponseDiaryUuid = $I->grabDataFromResponseByJsonPath('$.uuid')[0];
        $actualResponseNotes = $I->grabDataFromResponseByJsonPath('$.notes')[0];
        $actualResponseNotedAt = $I->grabDataFromResponseByJsonPath('$.notedAt')[0];

        $I->assertEquals($this->diary->getUuid(), $actualResponseDiaryUuid);
        $I->assertEquals($this->diary->getNotes(), $actualResponseNotes);
        $I->assertEquals($this->diary->getNotedAt(), new \DateTimeImmutable($actualResponseNotedAt));
    }

    public function testInvalidNotedAt(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{noted_at} Invalid NotedAt');

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];

        $I->amBearerAuthenticated($token);

        $I->sendGet('/api/diary/2021-02-01');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function testUnauthorised(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{noted_at} Unauthorised');

        $I->sendGet('/api/diary/2021-02-04');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
