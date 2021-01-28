<?php namespace App\Tests;

use App\Entity\User;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class JwtAuthCest
{
    public function _before(FunctionalTester $I)
    {
        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $I->grabService('security.password_encoder');
        $user = new User('user@example.com');
        $user->setPassword(
            $userPasswordEncoder->encodePassword($user, 'my-strong-password')
        );
        $I->haveInRepository($user);
    }

    public function testSuccessAuth(FunctionalTester $I)
    {
        $I->wantToTest('login with valid credentials');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'user@example.com',
            'password' => 'my-strong-password',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.refresh_token');
    }

    public function testIncorrectPassword(FunctionalTester $I)
    {
        $I->wantToTest('login with invalid credentials');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => 'random@example.com',
            'password' => 'random',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseJsonMatchesJsonPath('$.code');
        $I->seeResponseJsonMatchesJsonPath('$.message');

        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Invalid credentials.',
        ]);
    }

    public function testInvalidMethod(FunctionalTester $I)
    {
        $I->wantToTest('login with invalid method');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/api/login');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED);
    }

    public function testInvalidBody(FunctionalTester $I)
    {
        $I->wantToTest('login with invalid body');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }
}
