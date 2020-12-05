<?php

namespace App\Entity;

use App\Repository\MenchoSamayaRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=MenchoSamayaRepository::class)
 */
class MenchoSamaya
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $diaryUuid;

    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $mantraUuid;

    /**
     * @ORM\Column(type="integer")
     */
    private int $count;

    /**
     * @ORM\Column(type="integer")
     */
    private int $timeMinutes;

    public function __construct(UuidInterface $diaryUuid, UuidInterface $mantraUuid, int $count, int $timeMinutes)
    {
        $this->uuid = Uuid::uuid4();
        $this->diaryUuid = $diaryUuid;
        $this->mantraUuid = $mantraUuid;
        $this->count = $count;
        $this->timeMinutes = $timeMinutes;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getDiaryUuid(): UuidInterface
    {
        return $this->diaryUuid;
    }

    public function setDiaryUuid($diaryUuid): self
    {
        $this->diaryUuid = $diaryUuid;

        return $this;
    }

    public function getMantraUuid(): UuidInterface
    {
        return $this->mantraUuid;
    }

    public function setMantraUuid($mantraUuid): self
    {
        $this->mantraUuid = $mantraUuid;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getTimeMinutes(): int
    {
        return $this->timeMinutes;
    }

    public function setTimeMinutes(int $timeMinutes): self
    {
        $this->timeMinutes = $timeMinutes;

        return $this;
    }
}
