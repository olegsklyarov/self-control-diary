<?php

declare(strict_types=1);

namespace App\Controller\Mencho\GetAllMantras;

use App\Entity\MenchoMantra;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/api/mencho/mantra', name: 'get_mencho_mantras', methods: ['GET'])]
    public function getAllMantras(): Response
    {
        return $this->json(
            $this->getDoctrine()->getRepository(MenchoMantra::class)->findAll()
        );
    }
}
