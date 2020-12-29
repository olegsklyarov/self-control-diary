<?php

namespace App\Entity;

use App\Repository\DiaryRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=DiaryRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"user_uuid", "noted_at"})})
 */
class Diary
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Groups({"api"})
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api"})
     */
    private ?string $notes;

    /**
     * @ORM\Column(type="date")
     * @Groups({"api"})
     */
    private \DateTimeInterface $notedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user_uuid", nullable=false, referencedColumnName="uuid")
     */
    private User $user;


    public function __construct(User $user)
    {
        $this->uuid = Uuid::uuid4();
        $this->notedAt = new \DateTimeImmutable();
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
