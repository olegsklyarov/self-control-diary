<?php

declare(strict_types=1);

namespace App\Controller\Signup;

use Symfony\Component\Validator\Constraints as Assert;

final class SignupDTO
{
    #[Assert\Email(['message' => "The email '{{ value }}' is not a valid email."])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Type(['String'])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $password;
}
