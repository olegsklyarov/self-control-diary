<?php

namespace App\Controller;

use App\Entity\Diary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiaryController extends AbstractController
{
    /**
     * @Route("/api/diary/{noted_at}", name="get_diary", methods={"GET"})
     */
    public function getDiary(?Diary $diary): Response
    {
        return $diary === null
            ? new Response('', Response::HTTP_NOT_FOUND)
            : $this->json(
                $diary,
                Response::HTTP_OK,
                [],
                ['groups' => 'api'],
            );
    }
}
