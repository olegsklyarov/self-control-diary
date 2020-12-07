<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\MenchoMantra;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class MenchoMantraFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
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
