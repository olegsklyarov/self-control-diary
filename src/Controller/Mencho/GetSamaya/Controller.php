<?php

declare(strict_types=1);

namespace App\Controller\Mencho\GetSamaya;

use App\Entity\Diary;
use App\Service\Mencho\MenchoService;
use App\Service\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/api/mencho/{noted_at}",
 *     name="get_samaya",
 *     requirements={"noted_at": "\d{4}-\d{2}-\d{2}"},
 *     methods={"GET"},
 * )
 */
class Controller extends AbstractController
{
    private MenchoService $menchoService;

    public function __construct(MenchoService $menchoService)
    {
        $this->menchoService = $menchoService;
    }

    public function __invoke(?Diary $diary): Response
    {
        if (null === $diary) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'Diary not found.');
        }
        $menchoSamaya = $this->menchoService->getSamaya($diary);

        return $this->json($menchoSamaya, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
