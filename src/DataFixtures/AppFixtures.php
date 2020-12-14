<?php

namespace App\DataFixtures;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
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

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $manager->persist($mantraBuddhaShakyamuni);
        $manager->persist(new MenchoMantra('Будда Медицины', 1));
        $manager->persist(new MenchoMantra('Амитаба', 1));
        $manager->persist(new MenchoMantra('Ченрезиг', 1));
        $manager->persist(new MenchoMantra('Ваджрапани', 1));
        $manager->persist(new MenchoMantra('Хайягрива', 1));
        $manager->persist(new MenchoMantra('Падмасамбава/Гуру Ринпоче', 2));
        $manager->persist(new MenchoMantra('Красная Дакиня Симкхамукха', 2));
        $manager->persist(new MenchoMantra('Зеленая Тара', 2));
        $manager->persist(new MenchoMantra('Белый Махакала', 2));

        $manager->persist(new MenchoMantra('Махавайрочана', 3));
        $manager->persist(new MenchoMantra('Золотой Манджушри', 3));
        $manager->persist(new MenchoMantra('Амитаюс', 3));
        $manager->persist(new MenchoMantra('Кшитигарба', 3));
        $manager->persist(new MenchoMantra('Гуру Драгпо', 3));
        $manager->persist(new MenchoMantra('Синяя Дакиня Симкхамукха', 3));
        $manager->persist(new MenchoMantra('Миева/Ачала', 3));
        $manager->persist(new MenchoMantra('Фудо Дайкоку', 3));
        $manager->persist(new MenchoMantra('Хатчиман', 3));
        $manager->persist(new MenchoMantra('Курукулле', 3));
        $manager->persist(new MenchoMantra('Тсугтор Намджялма', 3));
        $manager->persist(new MenchoMantra('Ритрома', 3));
        $manager->persist(new MenchoMantra('Тройной-Воинственный/ТаЧагКьюнгСум', 3));
        $manager->persist(new MenchoMantra('Синганата', 3));
        $manager->persist(new MenchoMantra('Черный Махакала', 3));

        $manager->persist(new MenchoMantra('Белый Манджушри', 4));
        $manager->persist(new MenchoMantra('Защитник Ваджракилайя', 4));

        $diary = new Diary($user);
        $diary->setNotes('My first note');
        $manager->persist($diary);

        $running = new Running($diary, 4.7, 32, -11);
        $running->setHealthNotes('Чувствую себя великолепно! 🚀');
        $manager->persist($running);

        $menchoSamaya = new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100);
        $manager->persist($menchoSamaya);

        $manager->flush();
    }
}
