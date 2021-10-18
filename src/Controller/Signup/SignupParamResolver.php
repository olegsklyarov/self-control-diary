<?php

declare(strict_types=1);

namespace App\Controller\Signup;

use App\Exception\HttpRequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignupParamResolver implements ArgumentValueResolverInterface
{
    private const PARAM_EMAIL = 'email';
    private const PARAM_PASSWORD = 'password';

    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return SignupDTO::class === $argument->getType()
               && null !== $request->get(self::PARAM_EMAIL)
               && null !== $request->get(self::PARAM_PASSWORD);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $signupDTO = new SignupDTO();
        $signupDTO->email = $request->get(self::PARAM_EMAIL);
        $signupDTO->password = $request->get(self::PARAM_PASSWORD);

        $violations = $this->validator->validate($signupDTO);
        if (count($violations) > 0) {
            throw new HttpRequestValidationException($violations);
        }

        yield $signupDTO;
    }
}
