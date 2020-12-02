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
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private $diary_uuid;

    /**
     * @ORM\Column(type="integer")
     */
    private $distance_meters;

    /**
     * @ORM\Column(type="integer")
     */
    private $time_minutes;

    /**
     * @ORM\Column(type="integer")
     */
    private $temperature_celsius;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $health_notes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $party;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDistanceMeters(): ?int
    {
        return $this->distance_meters;
    }

    public function setDistanceMeters(int $distance_meters): self
    {
        $this->distance_meters = $distance_meters;

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

    public function getTemperatureCelsius(): ?int
    {
        return $this->temperature_celsius;
    }

    public function setTemperatureCelsius(int $temperature_celsius): self
    {
        $this->temperature_celsius = $temperature_celsius;

        return $this;
    }

    public function getHealthNotes(): ?string
    {
        return $this->health_notes;
    }

    public function setHealthNotes(?string $health_notes): self
    {
        $this->health_notes = $health_notes;

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
