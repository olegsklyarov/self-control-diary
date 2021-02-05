<?php

declare(strict_types=1);

namespace App\Controller\Diary\PostDiary;

use App\Controller\Diary\DiaryDTO;
use App\Service\Diary\DiaryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    private DiaryService $diaryService;

    public function __construct(DiaryService $diaryService)
    {
        $this->diaryService = $diaryService;
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
}
