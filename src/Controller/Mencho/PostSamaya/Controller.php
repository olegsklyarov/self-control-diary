<?php

declare(strict_types=1);

namespace App\Controller\Mencho\PostSamaya;

use App\Controller\Mencho\MenchoSamayaDTO;
use App\Service\Mencho\Exception\DiaryNotFoundException;
use App\Service\Mencho\Exception\MantraNotFoundException;
use App\Service\Mencho\Exception\MenchoSamayaAlreadyExistsException;
use App\Service\Mencho\MenchoService;
use App\Service\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/mencho/samaya', name: 'post_samaya', methods: ['POST'])]
class Controller extends AbstractController
{
    public function __construct(private MenchoService $menchoService)
    {
    }

    public function __invoke(MenchoSamayaDTO $menchoSamayaDTO): Response
    {
        try {
            $createdMenchoSamaya = $this->menchoService->persistFromDto($menchoSamayaDTO);
        } catch (DiaryNotFoundException) {
            return Util::errorJsonResponse(Response::HTTP_BAD_REQUEST, 'Diary not found.');
        } catch (MantraNotFoundException) {
            return Util::errorJsonResponse(Response::HTTP_BAD_REQUEST, 'Mantra not found.');
        } catch (MenchoSamayaAlreadyExistsException) {
            return Util::errorJsonResponse(Response::HTTP_CONFLICT, 'Already exists.');
        }

        return $this->json($createdMenchoSamaya, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
