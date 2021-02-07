<?php

declare(strict_types=1);

namespace App\Controller\Diary\GetDiary;

use App\Entity\Diary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    /**
     * @Route("/api/diary/{noted_at}", name="get_diary", methods={"GET"})
     */
    public function getDiary(?Diary $diary): Response
    {
        return null === $diary
            ? new Response('', Response::HTTP_NOT_FOUND)
            : $this->json($diary, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
