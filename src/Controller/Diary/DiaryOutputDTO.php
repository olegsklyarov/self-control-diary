<?php

declare(strict_types=1);

namespace App\Controller\Diary;

final class DiaryOutputDTO
{
    public string $uuid;
    public ?string $notes;
    public string $notedAt;
}
