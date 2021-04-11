<?php

declare(strict_types=1);

namespace App\Controller\Signup\PostSignup;

use App\Controller\Signup\SignupDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/signup', name: 'post_signup', methods: ['POST'])]
class Controller extends AbstractController
{
    public function __invoke(SignupDTO $signupDTO): Response
    {

        return $this->json('', Response::HTTP_OK, [], ['groups' => 'api']);
    }
}
