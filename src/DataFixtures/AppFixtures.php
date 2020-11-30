<?php

namespace App\DataFixtures;

use App\Entity\MenchoMantra;
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

        $manager->persist(new MenchoMantra('Будда Шакьямуни', 1));
        $manager->persist(new MenchoMantra('Будда Медицины', 1));
        $manager->persist(new MenchoMantra('Амитаба', 1));
        $manager->persist(new MenchoMantra('Ченрезиг', 1));
        $manager->persist(new MenchoMantra('Ваджрапани', 1));
        $manager->persist(new MenchoMantra('Хайягрива', 1));
        $manager->persist(new MenchoMantra('Падмасамбава/Гуру Ринпоче', 2));
        $manager->persist(new MenchoMantra('Красная Дакиня Симкхамукха', 2));
        $manager->persist(new MenchoMantra('Зеленая Тара', 2));
        $manager->persist(new MenchoMantra('Белый Махакала', 2));
        $manager->flush();
    }
}
