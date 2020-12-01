<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", methods={"POST"})
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $apiToken = bin2hex(random_bytes(60));
        $apiTokenExpiresAt = new \DateTimeImmutable('+1 day');

        $user->setLastVisitAt(new \DateTimeImmutable());
        $user->setApiToken($apiToken);
        $user->setApiTokenExpiresAt($apiTokenExpiresAt);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([
            'token' => $apiToken,
            'expires_at' => $apiTokenExpiresAt->format(\DateTimeImmutable::ISO8601),
        ]);
    }
}
