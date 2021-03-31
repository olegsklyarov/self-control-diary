<?php

declare(strict_types=1);

namespace App\Controller\Mencho\DeleteMenchoSamayaByDiaryAndMantra;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Service\Mencho\MenchoSamayaService;
use App\Service\Mencho\MenchoService;
use App\Service\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/mencho/samaya/{noted_at}/{mantra_uuid}', name: 'delete_mencho_samaya_by_diary_and_mantra', methods: ['DELETE'])]
class Controller extends AbstractController
{
    public function __construct(
        private MenchoService $menchoService,
        private MenchoSamayaService $menchoSamayaService
    ) {
    }

    public function __invoke(?Diary $diary, ?MenchoMantra $menchoMantra): Response
    {
        if (null === $diary) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'Diary not found.');
        }
        if (null === $menchoMantra) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'MenchoSamaya not found.');
        }
        $menchoSamaya = $this->menchoSamayaService->findByDiaryAndMantra($diary, $menchoMantra);

        if (null === $menchoSamaya) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'MenchoSamaya not found for diary and mantra.');
        }
        $this->menchoService->deleteSamayaByUuid($menchoSamaya);

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => 'api']);
    }
}
