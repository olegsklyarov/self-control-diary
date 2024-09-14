<?php

declare(strict_types=1);

namespace App\Controller\Diary;

use App\SelfValidationInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class DiaryInputDTO implements SelfValidationInterface
{
    /**
     * @Assert\AtLeastOneOf({
     *
     *     @Assert\Length(min=1),
     *
     *     @Assert\IsNull
     * })
     */
    public ?string $notes;

    #[Assert\Date]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $notedAt;

    public function validate(): array
    {
        if (!array_key_exists('notes', get_object_vars($this))) {
            return [
                'notes' => 'property should exists',
            ];
        }

        return [];
    }
}
