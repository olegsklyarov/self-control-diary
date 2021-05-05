<?php

declare(strict_types=1);

namespace App\Controller\Signup\PostSignup;

use App\Controller\Signup\SignupDTO;
use App\Service\Signup\Exception\InvalidEmailException;
use App\Service\Signup\Exception\LeadIsAlreadyExistException;
use App\Service\Signup\Exception\UserIsAlreadyExistException;
use App\Service\Signup\SignupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/signup', name: 'post_signup', methods: ['POST'])]
class Controller extends AbstractController
{
    public function __construct(private SignupService $signupService)
    {
    }

    public function __invoke(SignupDTO $signupDTO): Response
    {
        try {
            $createdLead = $this->signupService->persistFromDto($signupDTO);
        } catch (UserIsAlreadyExistException) {
            return $this->json(['code' => Response::HTTP_CONFLICT, 'message' => 'Such email already signed up.'], Response::HTTP_CONFLICT);
        } catch (InvalidEmailException) {
            return $this->json(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Please specify correct email address.'], Response::HTTP_BAD_REQUEST);
        } catch (LeadIsAlreadyExistException) {
            return $this->json(['code' => Response::HTTP_FORBIDDEN, 'message' => 'Please check your email inbox and follow verification link.'], Response::HTTP_FORBIDDEN);
        }

        return $this->json(['code' => Response::HTTP_OK, 'message' => 'Please check your email inbox and follow verification link.'], Response::HTTP_OK);
    }
}
