<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class DespesaDTO extends AbstractEntity
{
    public function __construct(
        #[Assert\Range(min: 0, max: 1, notInRangeMessage: 'Campo tipo: somente 0 ou 1')]
        public int $tipo = 0,
        public float $valor = 0,
        public ?string $descricao = null,
        public ?User $user = null
    )
    {
    }
}