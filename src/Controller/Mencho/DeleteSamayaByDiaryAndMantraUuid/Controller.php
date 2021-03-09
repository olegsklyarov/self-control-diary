<?php

declare(strict_types=1);

namespace App\Controller\Mencho\DeleteSamayaByDiaryAndMantraUuid;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Service\Mencho\MenchoSamayaService;
use App\Service\Mencho\MenchoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(
        private MenchoService $menchoService,
        private MenchoSamayaService $menchoSamayaService
    ) {
    }

    #[Route('/api/mencho/samaya/{noted_at}/{mantra_uuid}', name: 'delete_samaya_by_uuid', methods: ['DELETE'])]
    public function deleteSamayaByUuid(?Diary $diary, ?MenchoMantra $menchoMantra): Response
    {
        if (null === $diary) {
            return new Response('Diary not found.', Response::HTTP_NOT_FOUND);
        }
        if (null === $menchoMantra) {
            return new Response('Mantra not found.', Response::HTTP_NOT_FOUND);
        }
        $menchoSamaya = $this->menchoSamayaService->findByDiaryAndMantra($diary, $menchoMantra);
        $this->menchoService->deleteSamayaByUuid($menchoSamaya);

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => 'api']);
    }
}
