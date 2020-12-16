<?php

namespace App\Entity;

use App\Repository\RunningRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RunningRepository::class)
 */
class Running
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity=Diary::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="diary_uuid", nullable=false, referencedColumnName="uuid")
     */
    private Diary $diary;

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

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isSwam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $waterTemperatureCelsius;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $startedAt;


    public function __construct(Diary $diary, int $distanceMeters, int $timeMinutes, int $temperatureCelsius)
    {
        $this->diary = $diary;
        $this->distanceMeters = $distanceMeters;
        $this->timeMinutes = $timeMinutes;
        $this->temperatureCelsius = $temperatureCelsius;
    }

    public function getDiary(): Diary
    {
        return $this->diary;
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

    public function getIsSwam(): ?bool
    {
        return $this->isSwam;
    }

    public function setIsSwam(?bool $isSwam): self
    {
        $this->isSwam = $isSwam;

        return $this;
    }

    public function getWaterTemperatureCelsius(): ?int
    {
        return $this->waterTemperatureCelsius;
    }

    public function setWaterTemperatureCelsius(?int $waterTemperatureCelsius): self
    {
        $this->waterTemperatureCelsius = $waterTemperatureCelsius;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }
}
