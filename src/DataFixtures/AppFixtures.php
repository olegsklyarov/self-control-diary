<?php

namespace App\DataFixtures;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Entity\MenchoSamaya;
use App\Entity\Running;
use App\Entity\Sleeping;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const ADMIN_USER_EMAIL = 'admin@example.com';
    private const ADMIN_USER_PASSWORD = 'admin';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(self::ADMIN_USER_EMAIL);
        $user->setPassword($this->passwordEncoder->encodePassword($user, self::ADMIN_USER_PASSWORD));
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

        $sleeping = new Sleeping($diary);
        $awakeAt = new DateTimeImmutable('17.12.2020 9:00:00');
        $sleepAt = new DateTimeImmutable('17.12.2020 23:00:00');
        $sleeping->setAwakeAt($awakeAt)->setSleepAt($sleepAt);
        $manager->persist($sleeping);

        $manager->flush();
    }
}
