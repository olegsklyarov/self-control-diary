<?php

declare(strict_types=1);


namespace App\Service;


use App\Entity\Diary;
use App\Repository\MenchoSamayaRepository;


class MenchoService extends MenchoSamayaRepository
{

    public function getSamaya(Diary $diary): array
    {
        return $this->findByDiaryUuid($diary);
    }
}