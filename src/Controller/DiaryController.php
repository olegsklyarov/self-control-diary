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
            : $this->json($diary, Response::HTTP_OK, [], ['groups' => 'api']);
    }

    /**
     * @Route("/api/diary", name="post_diary", methods={"POST"})
     */
    public function postDiary(DiaryDTO $diaryDto): Response
    {
        $createdDiary = $this->diaryService->persistFromDto($diaryDto);
        return null === $createdDiary
            ? new Response('', Response::HTTP_CONFLICT)
            : $this->json($createdDiary, Response::HTTP_CREATED, [], ['groups' => 'api']);
    }

    /**
     * @Route("/api/diary", name="patch_diary", methods={"PATCH"})
     */
    public function patchDiary(DiaryDTO $diaryDto): Response
    {
        $updatedDiary = $this->diaryService->updateFromDTO($diaryDto);
        return null === $updatedDiary
            ? new Response('', Response::HTTP_CONFLICT)
            : $this->json($updatedDiary, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
