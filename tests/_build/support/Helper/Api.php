<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Entity\User;
use Codeception\Module;
use Codeception\Module\Symfony;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Api extends Module
{
    public function createUser($username = 'user@example.com', $password = 'my-strong-password'): User
    {
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
}
