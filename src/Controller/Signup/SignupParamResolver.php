<?php

declare(strict_types=1);

namespace App\Controller\Signup;

use App\Entity\Lead;
use App\Service\Signup\SignupService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class SignupParamResolver implements ArgumentValueResolverInterface
{
    private const PARAM_EMAIL = 'email';

    public function __construct(private SignupService $signupService)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return Lead::class === $argument->getType() && null !== $request->get(self::PARAM_EMAIL);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $paramEmail = $request->get(self::PARAM_EMAIL);
        yield $this->signupService->findByEmail($paramEmail);
    }
}
