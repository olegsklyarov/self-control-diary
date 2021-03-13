<?php

declare(strict_types=1);

namespace App\Controller\Diary\GetDiary;

use App\Entity\Diary;
use App\Service\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/api/diary/{noted_at}', name: 'get_diary', methods: ['GET'])]
    public function getDiary(?Diary $diary): Response
    {
        if (null === $diary) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'Diary not found.');
        }

        return $this->json($diary, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
