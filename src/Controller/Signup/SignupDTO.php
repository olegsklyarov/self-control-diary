<?php

declare(strict_types=1);

namespace App\Controller\Signup;

use Symfony\Component\Validator\Constraints as Assert;

final class SignupDTO
{
    #[Assert\Email]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Type('string')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $password;
}
