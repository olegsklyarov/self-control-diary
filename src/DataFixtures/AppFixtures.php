<?php

namespace App\DataFixtures;

use App\Entity\Diary;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const TEST_USER_EMAIL = 'test@example.com';
    private const TEST_USER_PASSWORD = 'strong-password';

    public function load(ObjectManager $manager)
    {
        $user = new User(
            self::TEST_USER_EMAIL,
            password_hash(self::TEST_USER_PASSWORD, PASSWORD_DEFAULT)
        );

        $diary = new Diary('My first note', $user);
        $manager->persist($user);
        $manager->persist($diary);
        $manager->flush();
    }
}
