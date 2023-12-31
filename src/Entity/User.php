<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups('public')]
    private string $email;

    #[ORM\Column]
    #[Ignore]
    private array $roles = [];

    #[ORM\Column]
    #[Ignore]
    private string $password;

    #[ORM\Column(nullable: true)]
    #[Groups('public')]
    private ?float $montante = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Despesa::class, orphanRemoval: true)]
    #[Ignore]
    private Collection $despesas;

    public function __construct()
    {
        $this->despesas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    #[Ignore]
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
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

    public function getMontante(): ?string
    {
        if($this->montante){
            return number_format($this->montante, 2);
        }

        return $this->montante;
    }

    public function getMontanteReal(): float
    {
        if($this->montante){
            return $this->montante;
        }

        return 0;
    }

    public function setMontante(float $montante): self
    {
        $this->montante = $montante;
        return $this;
    }

    /**
     * @return Collection<int, Despesa>
     */
    public function getDespesas(): Collection
    {
        return $this->despesas;
    }

    public function addDespesa(Despesa $despesa): static
    {
        if (!$this->despesas->contains($despesa)) {
            $this->despesas->add($despesa);
            $despesa->setUser($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getEmail();
    }
}
