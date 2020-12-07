<?php

namespace App\DataFixtures;

use App\Entity\Diary;
use App\Entity\Running;
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
            password_hash(self::TEST_USER_PASSWORD, PASSWORD_DEFAULT),
        );
        $manager->persist($user);

        $diary = new Diary($user);
        $diary->setNotes('My first note');
        $manager->persist($diary);

        $running = new Running($diary, 4.7, 32, -11);
        $running->setHealthNotes('Чувствую себя великолепно! 🚀');
        $manager->persist($running);

        $manager->flush();
    }
}
