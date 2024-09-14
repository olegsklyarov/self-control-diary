<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MenchoMantraRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MenchoMantraRepository::class)
 */
class MenchoMantra
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="uuid", unique=true)
     *
     * @Groups({"api"})
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Groups({"api"})
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"api"})
     */
    private int $level;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    public function __construct(string $name, int $level, ?string $description = null)
    {
        $this->uuid = Uuid::uuid4();
        $this->name = $name;
        $this->level = $level;
        $this->description = $description;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
