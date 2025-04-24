<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(nullable: true)]
    private ?int $SilverCoins = null;

    #[ORM\Column(nullable: true)]
    private ?int $goldCoins = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $transactionMade;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phonenumber = null;

    public function __construct()
    {
        $this->transactionMade = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getSilverCoins(): ?int
    {
        return $this->SilverCoins;
    }

    public function setSilverCoins(?int $SilverCoins): static
    {
        $this->SilverCoins = $SilverCoins;

        return $this;
    }

    public function getGoldCoins(): ?int
    {
        return $this->goldCoins;
    }

    public function setGoldCoins(?int $goldCoins): static
    {
        $this->goldCoins = $goldCoins;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactionMade(): Collection
    {
        return $this->transactionMade;
    }

    public function addTransactionMade(Transaction $transactionMade): static
    {
        if (!$this->transactionMade->contains($transactionMade)) {
            $this->transactionMade->add($transactionMade);
            $transactionMade->setUser($this);
        }

        return $this;
    }

    public function removeTransactionMade(Transaction $transactionMade): static
    {
        if ($this->transactionMade->removeElement($transactionMade)) {
            // set the owning side to null (unless already changed)
            if ($transactionMade->getUser() === $this) {
                $transactionMade->setUser(null);
            }
        }

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }
}
