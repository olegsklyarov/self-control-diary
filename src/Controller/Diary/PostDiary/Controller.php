<?php

declare(strict_types=1);

namespace App\Controller\Diary\PostDiary;

use App\Controller\Diary\DiaryDTO;
use App\Service\Diary\DiaryService;
use App\Service\Diary\Exception\DiaryAlreadyExistsException;
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
        try {
            $createdDiary = $this->diaryService->persistFromDto($diaryDto);
        } catch (DiaryAlreadyExistsException $e) {
            return $this->json(
                [
                    'code' => Response::HTTP_CONFLICT,
                    'message' => 'Diary already exists',
                ],
                Response::HTTP_CONFLICT
            );
        }

        return $this->json($createdDiary, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
