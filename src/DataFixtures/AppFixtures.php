<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Entity\Running;
use App\Entity\Sleeping;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const ADMIN_USER_EMAIL = 'admin@example.com';
    private const ADMIN_USER_PASSWORD = 'admin';

    public function __construct(private UserPasswordEncoderInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User(self::ADMIN_USER_EMAIL);
        $user->setPassword($this->passwordEncoder->encodePassword($user, self::ADMIN_USER_PASSWORD));
        $manager->persist($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $manager->persist($mantraBuddhaShakyamuni);
        $mantraBuddhaMedicine = new MenchoMantra('Будда Медицины', 1);
        $manager->persist($mantraBuddhaMedicine);
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

        $diary = new Diary($user, new \DateTimeImmutable());
        $diary->setNotes('My first note');
        $manager->persist($diary);

        $running = new Running($diary, 4700, 32, -11);
        $running->setHealthNotes('Чувствую себя великолепно! 🚀');
        $running->setIsSwam(true)->setWaterTemperatureCelsius(10)->setStartedAt(new \DateTimeImmutable());
        $manager->persist($running);

        $manager->persist(new MenchoSamaya($diary, $mantraBuddhaShakyamuni, 100));
        $manager->persist(new MenchoSamaya($diary, $mantraBuddhaMedicine, 200));

        $sleeping = new Sleeping($diary);
        $awakeAt = new \DateTimeImmutable('17.12.2020 9:00:00');
        $sleepAt = new \DateTimeImmutable('17.12.2020 23:00:00');
        $sleeping->setAwakeAt($awakeAt)->setSleepAt($sleepAt);
        $manager->persist($sleeping);

        $manager->flush();
    }
}
