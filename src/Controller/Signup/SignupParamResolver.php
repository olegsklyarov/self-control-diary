<?php

declare(strict_types=1);

namespace App\Controller\Signup;

use App\Service\Signup\SignupService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class SignupParamResolver implements ArgumentValueResolverInterface
{
    private const PARAM_EMAIL = 'email';
    private const PARAM_PASSWORD = 'password';

    public function __construct(private SignupService $signupService)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return SignupDTO::class === $argument->getType() && null !== $request->get(self::PARAM_EMAIL) && null !== $request->get(self::PARAM_PASSWORD);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $signupDTO = new SignupDTO();
        $signupDTO->email = $request->get(self::PARAM_EMAIL);
        $signupDTO->password = $request->get(self::PARAM_PASSWORD);

        yield $signupDTO;
    }
}
