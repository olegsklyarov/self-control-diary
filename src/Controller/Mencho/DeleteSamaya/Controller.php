<?php

declare(strict_types=1);

namespace App\Controller\Mencho\DeleteSamaya;

use App\Entity\Diary;
use App\Service\Mencho\MenchoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/mencho/samaya/{noted_at}', name: 'delete_samaya', methods: ['DELETE'])]
class Controller extends AbstractController
{
    public function __construct(
        private MenchoService $menchoService
    ) {
    }

    public function __invoke(?Diary $diary): Response
    {
        if (null === $diary) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $this->menchoService->deleteSamayaByDiary($diary);

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => 'api']);
    }
}
