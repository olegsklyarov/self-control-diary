<?php

declare(strict_types=1);

namespace App\Controller\Signup;

use Symfony\Component\Validator\Constraints as Assert;

final class SignupDTO
{
    #[Assert\Email(message: 'Please specify correct email address.')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Type('string')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Regex('/^\w{8,100}$/', message: 'Your password length must be at least 8, contain latin letters and digits.')]
    public string $password;
}
