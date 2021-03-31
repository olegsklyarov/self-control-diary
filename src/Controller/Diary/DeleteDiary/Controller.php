<?php

declare(strict_types=1);

namespace App\Controller\Diary\DeleteDiary;

use App\Entity\Diary;
use App\Service\Diary\DiaryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/diary/{noted_at}', name: 'delete_diary', methods: ['DELETE'])]
final class Controller extends AbstractController
{
    public function __construct(private DiaryService $diaryService)
    {
    }

    public function __invoke(?Diary $diary): Response
    {
        if (null === $diary) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $this->diaryService->delete($diary);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
