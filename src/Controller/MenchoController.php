<?php

namespace App\Controller;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Service\MenchoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenchoController extends AbstractController
{
    /**
     * @Route("/api/mencho/mantra", name="mencho", methods={"GET"})
     */
    public function getAllMantras(): Response
    {
        return $this->json(
            $this->getDoctrine()->getRepository(MenchoMantra::class)->findAll()
        );
    }

    /**
     * @Route("/api/mencho/{noted_at}", name="getSamaya", methods={"GET"})
     */
    public function getSamaya(?Diary $diary): Response
    {
        if ($diary === null){
           return new Response('', Response::HTTP_NOT_FOUND);
        } else {
            $menschService = new MenchoService($this->getDoctrine());
            $data = $menschService->getSamaya($diary);
            $dump = $data;
            $data = json_encode($data, true);
            dump($dump);
            return $this->json($data, Response::HTTP_OK, [], ['groups' => 'api']);
        }
    }
}
