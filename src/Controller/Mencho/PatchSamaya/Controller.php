<?php

declare(strict_types=1);

namespace App\Controller\Mencho\PatchSamaya;

use App\Controller\Mencho\MenchoSamayaDTO;
use App\Service\Mencho\Exception\DiaryNotFoundException;
use App\Service\Mencho\Exception\MantraNotFoundException;
use App\Service\Mencho\Exception\MenchoSamayaNotFoundException;
use App\Service\Mencho\MenchoService;
use App\Service\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/mencho/samaya', name: 'patch_samaya', methods: ['PATCH'])]
class Controller extends AbstractController
{
    public function __construct(private MenchoService $menchoService)
    {
    }

    public function __invoke(MenchoSamayaDTO $menchoSamayaDTO): Response
    {
        try {
            $updatedMenchoSamaya = $this->menchoService->updateFromDto($menchoSamayaDTO);
        } catch (DiaryNotFoundException) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'Diary not found.');
        } catch (MantraNotFoundException) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'Mantra not found.');
        } catch (MenchoSamayaNotFoundException) {
            return Util::errorJsonResponse(Response::HTTP_NOT_FOUND, 'MenchoSamaya not found.');
        }

        return $this->json($updatedMenchoSamaya, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
