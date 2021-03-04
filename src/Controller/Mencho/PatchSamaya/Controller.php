<?php

declare(strict_types=1);

namespace App\Controller\Mencho\PatchSamaya;

use App\Controller\Mencho\MenchoSamayaDTO;
use App\Service\Mencho\Exception\DiaryNotFoundException;
use App\Service\Mencho\Exception\MantraNotFoundException;
use App\Service\Mencho\Exception\MenchoSamayaNotFoundException;
use App\Service\Mencho\MenchoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(private MenchoService $menchoService)
    {
    }

    #[Route('/api/mencho/samaya', name: 'patch_samaya', methods: ['PATCH'])]
    public function patchSamaya(MenchoSamayaDTO $menchoSamayaDTO): Response
    {
        try {
            $updatedMenchoSamaya = $this->menchoService->updateFromDto($menchoSamayaDTO);
        } catch (DiaryNotFoundException) {
            return $this->json(
                [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => 'Diary not found.',
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (MantraNotFoundException) {
            return $this->json(
                [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => 'Mantra not found.',
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (MenchoSamayaNotFoundException) {
            return $this->json(
                [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => 'MenchoSamaya not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json($updatedMenchoSamaya, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
