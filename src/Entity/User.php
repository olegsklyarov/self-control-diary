<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, nullable=false)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     * TODO add email validation using Symfony Validation
     */
    private string $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $lastVisitAt;

    /**
     * User constructor.
     * @param UuidInterface $uuid
     * @param string $email
     */
    public function __construct(UuidInterface $uuid, string $email)
    {
        $this->uuid = $uuid;
        $this->email = $email;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getLastVisitAt(): ?\DateTimeInterface
    {
        return $this->lastVisitAt;
    }

    public function setLastVisitAt(?\DateTimeInterface $lastVisitAt): self
    {
        $this->lastVisitAt = $lastVisitAt;

        return $this;
    }
}
