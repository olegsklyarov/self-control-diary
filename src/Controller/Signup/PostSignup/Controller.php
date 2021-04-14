<?php

declare(strict_types=1);

namespace App\Controller\Signup\PostSignup;

use App\Controller\Signup\SignupDTO;
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
        $createdLead = $this->signupService->persistFromDto($signupDTO);

        return $this->json(['code' => Response::HTTP_OK, 'message' => 'Please check your email inbox and follow verification link.', 'lead' => $createdLead], Response::HTTP_OK);
    }
}
