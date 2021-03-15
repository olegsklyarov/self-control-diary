<?php

declare(strict_types=1);

namespace App\Controller\Mencho\GetTotalSamaya;

use App\Service\Mencho\MenchoSamayaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(private MenchoSamayaService $menchoSamayaService)
    {
    }

    #[Route('/api/mencho/samaya', name: 'get_total_samaya', methods: ['GET'])]
    public function getTotalSamaya(): Response
    {
        return $this->json($this->menchoSamayaService->findTotalSamayaForCurrentUser(), Response::HTTP_OK);
    }
}
