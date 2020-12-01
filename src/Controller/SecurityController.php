<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login")
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->setLastVisitAt(new \DateTimeImmutable());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([
            'user' => 'Welcome, ' . $user->getUsername(),
        ]);
    }
}
