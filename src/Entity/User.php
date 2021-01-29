<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    private const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $passwordHash;

    /**
     * @ORM\Column(type="json", options={"default": "[]"})
     *
     * @var string[]
     */
    private array $roles;

    public function __construct(string $email, array $roles = [])
    {
        $this->uuid = Uuid::uuid4();
        $this->email = $email;
        $this->roles = $roles;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getLastVisitAt(): ?\DateTimeInterface
    {
        return $this->lastVisitAt;
    }

    public function setLastVisitAt(\DateTimeInterface $lastVisitAt): self
    {
        $this->lastVisitAt = $lastVisitAt;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function setPassword(string $encodedPassword): self
    {
        $this->passwordHash = $encodedPassword;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function getSalt(): void
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
