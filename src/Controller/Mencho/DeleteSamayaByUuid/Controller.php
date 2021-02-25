<?php

declare(strict_types=1);


namespace App\Controller\Mencho\DeleteSamayaByUuid;


use App\Entity\MenchoSamaya;
use App\Service\Mencho\MenchoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    private MenchoService $menchoService;

    public function __construct(MenchoService $menchoService)
    {
        $this->menchoService = $menchoService;
    }

    /**
     * @Route("/api/mencho/samaya/{noted_at}/{mantra_uuid}", name="delete_samaya_by_uuid", methods={"DELETE"})
     */
    public function deleteSamayaByUuid(?MenchoSamaya $menchoSamaya): Response
    {
        if (null === $menchoSamaya) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $this->menchoService->deleteSamayaByUuid($menchoSamaya);

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => 'api']);
    }
}