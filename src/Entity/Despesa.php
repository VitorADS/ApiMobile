<?php

namespace App\Entity;

use App\Helpers\TipoDespesa;
use App\Repository\DespesaRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DespesaRepository::class)]
#[HasLifecycleCallbacks]
class Despesa extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 1, notInRangeMessage: 'Campo tipo: somente 0 ou 1')]
    #[Groups('public')]
    private int $tipo;

    #[ORM\Column]
    #[Groups('public')]
    private float $valor;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('public')]
    private DateTime $data;

    #[ORM\Column(nullable: true)]
    #[Ignore]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('public')]
    private ?string $descricao = null;

    #[ORM\ManyToOne(inversedBy: 'despesas', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): string
    {
        return TipoDespesa::getOptionByKey($this->tipo);
    }

    public function setTipo(int $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getValor(): string
    {
        return number_format($this->valor, 2);
    }

    public function getValorReal(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): static
    {
        $this->valor = $valor;

        return $this;
    }

    public function getData(): DateTime
    {
        return $this->data;
    }

    public function setData(DateTime $data = new DateTime()): void
    {
        $this->data = $data;
    }

    // public function getUpdatedAt(): ?DateTime
    // {
    //     return $this->updatedAt;
    // }

    // #[PreUpdate]
    // public function setUpdatedAt(): void
    // {
    //     $this->updatedAt = new DateTime();
    // }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    #[PreUpdate]
    public function setUpdatedAt(PreUpdateEventArgs $eventArgs): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): static
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
