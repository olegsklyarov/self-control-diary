<?php

declare(strict_types=1);

namespace App\Controller\Signup\PostSignup;

use App\Controller\Signup\SignupDTO;
use App\Service\Signup\Exception\LeadIsAlreadyExistException;
use App\Service\Signup\Exception\UserIsAlreadyExistException;
use App\Service\Signup\SignupService;
use App\Service\Util;
use Doctrine\DBAL\ConnectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/signup", name="post_signup", methods={"POST"})
 */
class Controller extends AbstractController
{
    private SignupService $signupService;

    public function __construct(SignupService $signupService)
    {
        $this->signupService = $signupService;
    }

    public function __invoke(SignupDTO $signupDTO): Response
    {
        try {
            $this->signupService->persistFromDto($signupDTO);
        } catch (UserIsAlreadyExistException $e) {
            return Util::errorJsonResponse(Response::HTTP_CONFLICT, 'Such email already signed up.');
        } catch (LeadIsAlreadyExistException $e) {
            return Util::errorJsonResponse(Response::HTTP_FORBIDDEN, 'Please check your email inbox and follow verification link.');
        } catch (ConnectionException $e) {
            return Util::errorJsonResponse(Response::HTTP_FAILED_DEPENDENCY, 'Failed to save data in database');
        }

        return $this->json(['code' => Response::HTTP_OK, 'message' => 'Please check your email inbox and follow verification link.'], Response::HTTP_OK);
    }
}
