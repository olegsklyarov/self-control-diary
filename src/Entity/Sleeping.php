<?php

namespace App\Entity;

use App\Repository\SleepingRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SleepingRepository::class)
 */
class Sleeping
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity=Diary::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="diary_uuid", nullable=false, referencedColumnName="uuid")
     */
    private Diary $diary;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $awakeAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $sleepAt;

    public function __construct(Diary $diary)
    {
        $this->diary = $diary;
    }

    public function getDiary(): Diary
    {
        return $this->diary;
    }

    public function getAwakeAt(): ?DateTimeInterface
    {
        return $this->awakeAt;
    }

    public function setAwakeAt(?DateTimeInterface $awakeAt): self
    {
        $this->awakeAt = $awakeAt;

        return $this;
    }

    public function getSleepAt(): ?DateTimeInterface
    {
        return $this->sleepAt;
    }

    public function setSleepAt(?DateTimeInterface $sleepAt): self
    {
        $this->sleepAt = $sleepAt;

        return $this;
    }
}
