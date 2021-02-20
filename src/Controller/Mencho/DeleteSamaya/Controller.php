<?php

declare(strict_types=1);

namespace App\Controller\Mencho\DeleteSamaya;

use App\Entity\Diary;
use App\Service\Diary\DiaryService;
use App\Service\Mencho\MenchoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    private DiaryService $diaryService;
    private MenchoService $menchoServise;

    public function __construct(DiaryService $diaryService, MenchoService $menchoService)
    {
        $this->diaryService = $diaryService;
        $this->menchoServise = $menchoService;
    }

    /**
     * @Route("/api/mencho/samaya/{noted_at}", name="delete_samaya", methods={"DELETE"})
     */
    public function deleteSamaya(?Diary $diary): Response
    {
        if (null === $diary) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $this->menchoServise->deleteSamayaByDiary($diary);

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => 'api']);
    }
}
