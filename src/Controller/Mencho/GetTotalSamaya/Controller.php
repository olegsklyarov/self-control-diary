<?php

declare(strict_types=1);

namespace App\Controller\Mencho\GetTotalSamaya;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/api/mencho/samaya', name: 'get_total_samaya', methods: ['GET'])]
    public function getTotalSamaya(): Response
    {
        return new Response('Not Implemented yet', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
