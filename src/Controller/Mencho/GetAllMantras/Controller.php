<?php

declare(strict_types=1);

namespace App\Controller\Mencho\GetAllMantras;

use App\Entity\MenchoMantra;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/mencho/mantra", name="get_mencho_mantras", methods={"GET"})
 */
class Controller extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->json(
            $this->getDoctrine()->getRepository(MenchoMantra::class)->findAll()
        );
    }
}
