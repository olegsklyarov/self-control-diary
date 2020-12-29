<?php

namespace App\Controller;

use App\Entity\Diary;
use App\Service\DiaryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiaryController extends AbstractController
{
    private DiaryService $diaryService;

    public function __construct(DiaryService $diaryService)
    {
        $this->diaryService = $diaryService;
    }

    /**
     * @Route("/api/diary/{noted_at}", name="get_diary", methods={"GET"})
     */
    public function getDiary(?Diary $diary): Response
    {
        return $diary === null
            ? new Response('', Response::HTTP_NOT_FOUND)
            : $this->json(
                $diary,
                Response::HTTP_OK,
                [],
                ['groups' => 'api'],
            );
    }

    /**
     * @Route("/api/diary", name="post_diary", methods={"POST"})
     */
    public function postDiary(DiaryDTO $diaryDto): Response
    {
        if ($this->diaryService->isDiaryExistsByDto($diaryDto)) {
            return new Response('', Response::HTTP_CONFLICT);
        }

        $createdDiary = $this->diaryService->persistFromDto($diaryDto);

        return $this->json(
            $createdDiary,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'api'],
        );
    }
}
