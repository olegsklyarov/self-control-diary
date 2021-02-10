<?php

declare(strict_types=1);

namespace App\Controller\Mencho\PatchSamaya;

use App\Controller\Mencho\MenchoSamayaDTO;
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
     * @Route("/api/mencho/samaya", name="patch_samaya", methods={"PATCH"})
     */
    public function postSamaya(MenchoSamayaDTO $menchoSamayaDTO): Response
    {
        $updatedMenchoSamaya = '';

        return $this->json($updatedMenchoSamaya, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
