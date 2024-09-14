<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Entity\User;
use App\Tests\ApiTester;
use Codeception\Module;
use Codeception\Module\Symfony;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Api extends Module
{
    public const DEFAULT_USERNAME = 'user@example.com';
    public const DEFAULT_PASSWORD = 'my-strong-password';

    public function createUser(
        string $username = self::DEFAULT_USERNAME,
        string $password = self::DEFAULT_PASSWORD,
    ): User {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');

        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $symfony->grabService('security.password_encoder');
        $user = new User($username);
        $user->setPassword(
            $userPasswordEncoder->encodePassword($user, $password)
        );

        return $user;
    }

    public function doAuthAndGetJwtToken(
        ApiTester $I,
        string $username = self::DEFAULT_USERNAME,
        string $password = self::DEFAULT_PASSWORD,
    ): string {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/login', [
            'username' => $username,
            'password' => $password,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseJsonMatchesJsonPath('$.token');

        return $I->grabDataFromResponseByJsonPath('$.token')[0];
    }
}
