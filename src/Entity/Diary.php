<?php

namespace App\Entity;

use App\Repository\DiaryRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=DiaryRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"user_uuid", "noted_at"})})
 */
class Diary
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $notes;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $notedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user_uuid", nullable=false, referencedColumnName="uuid")
     */
    private User $user;


    public function __construct(?string $notes, User $user)
    {
        $this->uuid = Uuid::uuid4();
        $this->notedAt = new \DateTimeImmutable();
        $this->notes = $notes;
        $this->user = $user;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getNotedAt(): ?\DateTimeInterface
    {
        return $this->notedAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}