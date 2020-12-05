<?php

namespace App\Entity;

use App\Repository\MenchoSamayaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenchoSamayaRepository::class)
 */
class MenchoSamaya
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private $uuid;

    /**
     * @ORM\Column(type="uuid")
     */
    private $diary_uuid;

    /**
     * @ORM\Column(type="uuid")
     */
    private $mantra_uuid;

    /**
     * @ORM\Column(type="integer")
     */
    private $count;

    /**
     * @ORM\Column(type="integer")
     */
    private $time_minutes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getDiaryUuid()
    {
        return $this->diary_uuid;
    }

    public function setDiaryUuid($diary_uuid): self
    {
        $this->diary_uuid = $diary_uuid;

        return $this;
    }

    public function getMantraUuid()
    {
        return $this->mantra_uuid;
    }

    public function setMantraUuid($mantra_uuid): self
    {
        $this->mantra_uuid = $mantra_uuid;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getTimeMinutes(): ?int
    {
        return $this->time_minutes;
    }

    public function setTimeMinutes(int $time_minutes): self
    {
        $this->time_minutes = $time_minutes;

        return $this;
    }
}
