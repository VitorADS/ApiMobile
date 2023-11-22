<?php

namespace App\Services;

use App\DTO\DespesaDTO;
use App\Entity\AbstractEntity;
use App\Entity\Despesa;
use App\Helpers\TipoDespesa;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DespesaService extends AbstractService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Despesa::class);
    }

    /** @param DespesaDTO $entity */
    public function save(AbstractEntity $entity, ?int $id = null): AbstractEntity
    {
        try{            
            $despesa = new Despesa();
            $despesa->setTipo($entity->tipo);
            $despesa->setValor($entity->valor);
            $despesa->setUser($entity->user);
            $despesa->setDescricao($entity->descricao);
            $despesa = $this->atualizaMontante($despesa);

            return parent::save($despesa, $id);
        }catch(Exception $e){
            throw $e;
        }
    }

    /** @param Despesa $entity */
    public function remove(AbstractEntity $entity): bool
    {
        $userService = new UserService($this->entityManager);
        $user = $entity->getUser();

        $this->entityManager->beginTransaction();
        try{
            if($entity->getTipo() === TipoDespesa::DEBITO_STR){
                $user->setMontante(
                    $user->getMontante() + $entity->getValor()
                );
            } else {
                $user->setMontante(
                    $user->getMontante() - $entity->getValor()
                );
            }

            $userService->save($user, $user->getId());
            $result = parent::remove($entity);

            $this->entityManager->commit();
            return $result;
        }catch(Exception $e){
            $this->entityManager->rollback();
            throw $e;
        }
    }

    private function atualizaMontante(Despesa $despesa): Despesa
    {
        if($despesa->getTipo() === TipoDespesa::DEBITO_STR){
            $despesa->getUser()->setMontante(
                $despesa->getUser()->getMontante() - $despesa->getValor()
            );
        } else {
            $despesa->getUser()->setMontante(
                $despesa->getUser()->getMontante() + $despesa->getValor()
            );
        }

        return $despesa;
    }
}