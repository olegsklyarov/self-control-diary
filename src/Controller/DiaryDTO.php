<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Validator\Constraints as Assert;

final class DiaryDTO
{
    /**
     * @Assert\NotBlank
     */
    public ?string $notes;

    /**
     * @Assert\Date
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    public string $notedAt;
}
