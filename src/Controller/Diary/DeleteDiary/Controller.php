<?php

declare(strict_types=1);

namespace App\Controller\Diary\DeleteDiary;

use App\Entity\Diary;
use App\Service\Diary\DiaryService;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @SWG\Delete(
 *     summary="Delete diary with all related entities",
 *     @SWG\Response(
 *         response=204,
 *         description="Diary with all related entities successfully deleted",
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Invalid noted_at value",
 *     ),
 *     @SWG\Response(
 *         response="404",
 *         description="Diary not found",
 *     ),
 * )
 */
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
