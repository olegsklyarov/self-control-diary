<?php

declare(strict_types=1);

namespace App\Controller\Mencho\PostSamaya;

use App\Controller\Mencho\MenchoSamayaDTO;
use App\Service\Mencho\Exception\DiaryNotFoundException;
use App\Service\Mencho\Exception\MantraNotFoundException;
use App\Service\Mencho\Exception\MenchoSamayaAlreadyExistsException;
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
     * @Route("/api/mencho/samaya", name="post_samaya", methods={"POST"})
     */
    public function postSamaya(MenchoSamayaDTO $menchoSamayaDTO): Response
    {
        try {
            $createdMenchoSamaya = $this->menchoService->persistFromDto($menchoSamayaDTO);
        } catch (DiaryNotFoundException $e) {
            return $this->json(
                [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Diary not found.',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (MantraNotFoundException $e) {
            return $this->json(
                [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Mantra not found.',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (MenchoSamayaAlreadyExistsException $e) {
            return $this->json(
                [
                    'code' => Response::HTTP_CONFLICT,
                    'message' => 'Already exists.',
                ],
                Response::HTTP_CONFLICT
            );
        }

        return $this->json($createdMenchoSamaya, Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
