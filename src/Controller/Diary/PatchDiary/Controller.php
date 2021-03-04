<?php

declare(strict_types=1);

namespace App\Controller\Diary\PatchDiary;

use App\Controller\Diary\DiaryDTO;
use App\Service\Diary\DiaryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(private DiaryService $diaryService)
    {
    }

    #[Route('/api/diary', name: 'patch_diary', methods: ['PATCH'])]
    public function patchDiary(DiaryDTO $diaryDto): Response
    {
        $updatedDiary = $this->diaryService->updateFromDTO($diaryDto);

        return null === $updatedDiary
            ? new Response('', Response::HTTP_NOT_FOUND)
            : $this->json($updatedDiary, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
