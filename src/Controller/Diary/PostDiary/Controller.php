<?php

declare(strict_types=1);

namespace App\Controller\Diary\PostDiary;

use App\Controller\Diary\DiaryDTO;
use App\Service\Diary\DiaryService;
use App\Service\Diary\Exception\DiaryAlreadyExistsException;
use App\Service\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(private DiaryService $diaryService)
    {
    }

    #[Route('/api/diary', name: 'post_diary', methods: ['POST'])]
    public function postDiary(DiaryDTO $diaryDto): Response
    {
        try {
            $createdDiary = $this->diaryService->persistFromDto($diaryDto);
        } catch (DiaryAlreadyExistsException) {
            return Util::errorJsonResponse(Response::HTTP_CONFLICT, 'Diary already exists.');
        }

        return $this->json($createdDiary, Response::HTTP_CREATED, [], ['groups' => 'api']);
    }
}
