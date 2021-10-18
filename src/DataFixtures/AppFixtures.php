<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Diary;
use App\Entity\Lead;
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

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User(self::ADMIN_USER_EMAIL);
        $user->addRole(User::ROLE_DEVELOPER);
        $user->setPassword($this->passwordEncoder->encodePassword($user, self::ADMIN_USER_PASSWORD));
        $manager->persist($user);

        $mantraBuddhaShakyamuni = new MenchoMantra('Будда Шакьямуни', 1);
        $manager->persist($mantraBuddhaShakyamuni);
        $mantraBuddhaMedicine = new MenchoMantra('Будда Медицины', 1);
        $manager->persist($mantraBuddhaMedicine);
        $mantraBuddhaAmitaba = new MenchoMantra('Амитаба', 1);
        $manager->persist($mantraBuddhaAmitaba);
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

        $diary1 = new Diary($user, new \DateTimeImmutable());
        $diary1->setNotes('My first note');
        $manager->persist($diary1);

        $diary2 = new Diary($user, new \DateTimeImmutable('-1 day'));
        $manager->persist($diary2);

        $running = new Running($diary1, 4700, 32, -11);
        $running->setHealthNotes('Чувствую себя великолепно! 🚀');
        $running->setIsSwam(true)->setWaterTemperatureCelsius(10)->setStartedAt(new \DateTimeImmutable());
        $manager->persist($running);

        $manager->persist(new MenchoSamaya($diary1, $mantraBuddhaShakyamuni, 100));
        $manager->persist(new MenchoSamaya($diary1, $mantraBuddhaMedicine, 200));

        $manager->persist(new MenchoSamaya($diary2, $mantraBuddhaShakyamuni, 400));
        $manager->persist(new MenchoSamaya($diary2, $mantraBuddhaAmitaba, 100));

        $sleeping = new Sleeping($diary1);
        $awakeAt = new \DateTimeImmutable('17.12.2020 9:00:00');
        $sleepAt = new \DateTimeImmutable('17.12.2020 23:00:00');
        $sleeping->setAwakeAt($awakeAt)->setSleepAt($sleepAt);
        $manager->persist($sleeping);

        $lead = new Lead('lead@example.com', $this->passwordEncoder->encodePassword($user, 'lead-password'));
        $manager->persist($lead);

        $manager->flush();
    }
}
