<?php

declare(strict_types=1);

namespace App\Controller\Diary;

use Symfony\Component\Validator\Constraints as Assert;

final class DiaryDTO
{
    /**
     * @Assert\AtLeastOneOf({
     *     @Assert\Length(min=1),
     *     @Assert\IsNull
     * })
     */
    public ?string $notes = null;

    /**
     * @Assert\Date
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    public string $notedAt;
}
