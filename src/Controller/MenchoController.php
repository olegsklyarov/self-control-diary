<?php

namespace App\Controller;

use App\Entity\MenchoMantra;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenchoController extends AbstractController
{
    /**
     * @Route("/api/mencho/mantra", name="mencho", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json(
            $this->getDoctrine()->getRepository(MenchoMantra::class)->findAll()
        );
    }
}
