<?php

declare(strict_types=1);

namespace App\Controller\Diary\GetDiary;

use App\Controller\Diary\DiaryOutputDTO;
use App\Entity\Diary;
use App\Service\Util;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/diary/{noted_at}", name="get_diary", methods={"GET"})
 *
 * @SWG\Get(
 *     summary="Get diary",
 *     tags={"Diary"},
 *     @SWG\Response(
 *         response="200",
 *         description="Diary properties",
 *         @SWG\Schema(
 *             type="object",
 *             ref=@Model(type=DiaryOutputDTO::class),
 *         ),
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Invalid noted_at value",
 *     ),
 *     @SWG\Response(
 *         response="401",
 *         description="Unauthorized",
 *     ),
 *     @SWG\Response(
 *         response="404",
 *         description="Diary not found"
 *     ),
 * )
 */
class Controller extends AbstractController
{
    public function __invoke(?Diary $diary): Response
    {
        if (null === $diary) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'Diary not found.');
        }

        $diaryOutputDTO = new DiaryOutputDTO();
        $diaryOutputDTO->uuid = $diary->getUuid()->toString();
        $diaryOutputDTO->notes = $diary->getNotes();
        $diaryOutputDTO->notedAt = $diary->getNotedAt()->format(DATE_ATOM);

        return $this->json($diaryOutputDTO, Response::HTTP_OK);
    }
}
