<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Diary;
use App\Entity\MenchoMantra;
use App\Service\MenchoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenchoController extends AbstractController
{
    private MenchoService $menchoService;

    public function __construct(MenchoService $menchoService)
    {
        $this->menchoService = $menchoService;
    }

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
        if (null === $diary) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $menchoSamaya = $this->menchoService->getSamaya($diary);

        return $this->json($menchoSamaya, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
