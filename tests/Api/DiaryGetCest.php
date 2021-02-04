<?php
declare(strict_types=1);

namespace App\Tests\Api;
use App\Entity\Diary;
use App\Entity\User;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DiaryGetCest
{
    public function _before(ApiTester $I)
    {
        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $I->grabService('security.password_encoder');
        $user = new User('user@example.com');
        $user->setPassword(
            $userPasswordEncoder->encodePassword($user, 'my-strong-password')
        );
        $I->haveInRepository($user);

        $diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($diary);
    }

    public function testSuccess(ApiTester $I)
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
    }

    public function testInvalidNotedAt(ApiTester $I)
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

    public function testUnauthorised(ApiTester $I)
    {
        $I->wantToTest('GET /api/diary/{noted_at} Unauthorised');

        $I->sendGet('/api/diary/2021-02-04');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
