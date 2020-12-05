<?php

namespace App\Entity;

use App\Repository\RunningRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=RunningRepository::class)
 */
class Running
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $diaryUuid;

    /**
     * @ORM\Column(type="integer")
     */
    private int $distanceMeters;

    /**
     * @ORM\Column(type="integer")
     */
    private int $timeMinutes;

    /**
     * @ORM\Column(type="integer")
     */
    private int $temperatureCelsius;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $healthNotes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $party;


    public function __construct(int $distanceMeters, int $timeMinutes, int $temperatureCelsius)
    {
        $this->diaryUuid = Uuid::uuid4();
        $this->distanceMeters = $distanceMeters;
        $this->timeMinutes = $timeMinutes;
        $this->temperatureCelsius = $temperatureCelsius;
    }

    public function getDiaryUuid(): UuidInterface
    {
        return $this->diaryUuid;
    }

    public function getDistanceMeters(): int
    {
        return $this->distanceMeters;
    }

    public function setDistanceMeters(int $distanceMeters): self
    {
        $this->distanceMeters = $distanceMeters;

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

    public function getTemperatureCelsius(): int
    {
        return $this->temperatureCelsius;
    }

    public function setTemperatureCelsius(int $temperatureCelsius): self
    {
        $this->temperatureCelsius = $temperatureCelsius;

        return $this;
    }

    public function getHealthNotes(): ?string
    {
        return $this->healthNotes;
    }

    public function setHealthNotes(?string $healthNotes): self
    {
        $this->healthNotes = $healthNotes;

        return $this;
    }

    public function getParty(): ?string
    {
        return $this->party;
    }

    public function setParty(?string $party): self
    {
        $this->party = $party;

        return $this;
    }
}
