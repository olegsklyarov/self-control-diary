<?php

declare(strict_types=1);

namespace App\Controller\Diary\PostDiary;

use App\Controller\Diary\DiaryInputDTO;
use App\Service\Diary\DiaryService;
use App\Service\Diary\Exception\DiaryAlreadyExistsException;
use App\Service\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/diary", name="post_diary", methods={"POST"})
 */
class Controller extends AbstractController
{
    private DiaryService $diaryService;

    public function __construct(DiaryService $diaryService)
    {
        $this->diaryService = $diaryService;
    }

    public function __invoke(DiaryInputDTO $diaryDto): Response
    {
        try {
            $createdDiary = $this->diaryService->persistFromDto($diaryDto);
        } catch (DiaryAlreadyExistsException $e) {
            return Util::errorJsonResponse(Response::HTTP_CONFLICT, 'Diary already exists.');
        }

        return $this->json($createdDiary, Response::HTTP_CREATED, [], ['groups' => 'api']);
    }
}
