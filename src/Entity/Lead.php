<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LeadRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=LeadRepository::class)
 * @ORM\Table(name="lead")
 */
class Lead
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $passwordHash;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $verifiedEmailAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $verificationEmailSentAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $verificationToken;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getVerifiedEmailAt(): ?\DateTimeInterface
    {
        return $this->verifiedEmailAt;
    }

    public function setVerifiedEmailAt(\DateTimeInterface $verifiedEmailAt): self
    {
        $this->verifiedEmailAt = $verifiedEmailAt;

        return $this;
    }

    public function getVerificationEmailSentAt(): ?\DateTimeInterface
    {
        return $this->verificationEmailSentAt;
    }

    public function setVerificationEmailSentAt(\DateTimeInterface $verificationEmailSentAt): self
    {
        $this->verificationEmailSentAt = $verificationEmailSentAt;

        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(string $verificationToken): self
    {
        $this->verificationToken = $verificationToken;

        return $this;
    }
}
