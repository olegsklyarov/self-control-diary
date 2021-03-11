<?php

declare(strict_types=1);

namespace App\Controller\Mencho\DeleteMenchoSamayaByDiaryAndMantra;

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

    #[Route('/api/mencho/samaya/{noted_at}/{mantra_uuid}', name: 'delete_mencho_samaya_by_diary_and_mantra', methods: ['DELETE'])]
    public function deleteMenchoSamayaByDiaryAndMantra(?Diary $diary, ?MenchoMantra $menchoMantra): Response
    {
        if (null === $diary) {
            return $this->json([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Diary not found.',
            ], Response::HTTP_NOT_FOUND, [], ['groups' => 'api']);
        }
        if (null === $menchoMantra) {
            return $this->json([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'MenchoSamaya not found.',
            ], Response::HTTP_NOT_FOUND, [], ['groups' => 'api']);
        }
        $menchoSamaya = $this->menchoSamayaService->findByDiaryAndMantra($diary, $menchoMantra);

        if (null === $menchoSamaya) {
            return $this->json([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'MenchoSamaya not found for diary and mantra.',
            ], Response::HTTP_NOT_FOUND, [], ['groups' => 'api']);
        }
        $this->menchoService->deleteSamayaByUuid($menchoSamaya);

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => 'api']);
    }
}
