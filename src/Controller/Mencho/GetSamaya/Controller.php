<?php

declare(strict_types=1);

namespace App\Controller\Mencho\GetSamaya;

use App\Entity\Diary;
use App\Service\Mencho\MenchoService;
use App\Service\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(private MenchoService $menchoService)
    {
    }

    #[Route('/api/mencho/{noted_at}', name: 'get_samaya', methods: ['GET'])]
    public function getSamaya(?Diary $diary): Response
    {
        if (null === $diary) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'Diary not found.');
        }
        $menchoSamaya = $this->menchoService->getSamaya($diary);

        return $this->json($menchoSamaya, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
