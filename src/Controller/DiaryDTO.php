<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Validator\Constraints as Assert;

final class DiaryDTO
{
    /**
     * @Assert\NotBlank()
     */
    private ?string $notes;

    /**
     * @Assert\Date()
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private \DateTimeImmutable $notedAt;

    public function getNotedAt(): \DateTimeImmutable
    {
        return $this->notedAt;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
