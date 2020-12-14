<?php

namespace App\Controller;

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
        return $this->json( [
            'message' => 'List of mantras',
        ]);
    }
}
