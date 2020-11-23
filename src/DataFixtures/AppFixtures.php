<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User(
            Uuid::uuid4(),
            'test@example.com',
        );
        $manager->persist($user);
        $manager->flush();
    }
}
