<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Validator\Constraints as Assert;

final class MenchoSamayaDTO
{
    /**
     * @Assert\Date
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    public string $notedAt;

    /**
     * @Assert\Uuid
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    public string $mantraUuid;

    /**
     * @Assert\Positive
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    public int $count;

    /**
     * @Assert\Positive
     */
    public ?int $timeMinutes = null;
}
