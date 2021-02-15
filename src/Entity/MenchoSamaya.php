<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MenchoSamayaRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MenchoSamayaRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"diary_uuid", "mantra_uuid"})})
 */
class MenchoSamaya
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Groups({"api"})
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api"})
     */
    private int $count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"api"})
     */
    private ?int $timeMinutes;

    /**
     * @ORM\ManyToOne(targetEntity=Diary::class)
     * @ORM\JoinColumn(name="diary_uuid", nullable=false, referencedColumnName="uuid", onDelete="CASCADE")
     */
    private Diary $diary;

    /**
     * @ORM\ManyToOne(targetEntity=MenchoMantra::class)
     * @ORM\JoinColumn(name="mantra_uuid", nullable=false, referencedColumnName="uuid", onDelete="CASCADE")
     * @Groups({"api"})
     */
    private MenchoMantra $menchoMantra;

    public function __construct(Diary $diary, MenchoMantra $menchoMantra, int $count, ?int $timeMinutes)
    {
        $this->uuid = Uuid::uuid4();
        $this->diary = $diary;
        $this->menchoMantra = $menchoMantra;
        $this->count = $count;
        $this->timeMinutes = $timeMinutes;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
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

    public function getTimeMinutes(): ?int
    {
        return $this->timeMinutes;
    }

    public function setTimeMinutes(?int $timeMinutes): self
    {
        $this->timeMinutes = $timeMinutes;

        return $this;
    }

    public function getDiary(): Diary
    {
        return $this->diary;
    }

    public function getMenchoMantra(): MenchoMantra
    {
        return $this->menchoMantra;
    }

    public function setMenchoMantra(MenchoMantra $menchoMantra): self
    {
        $this->menchoMantra = $menchoMantra;

        return $this;
    }
}
