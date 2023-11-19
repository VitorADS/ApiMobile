<?php

namespace App\Services;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;

abstract class AbstractService
{
    protected EntityRepository $repository;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        string $entityClass
    )
    {
        $this->repository = $entityManager->getRepository($entityClass);
    }

    public function getRepository(): EntityRepository
    {
        return $this->repository;
    }

    public function save(AbstractEntity $entity, ?int $id = null): AbstractEntity
    {
        $this->entityManager->beginTransaction();
        try{
            if(!$id){
                $this->entityManager->persist($entity);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $entity;
        }catch(Exception $e){
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function remove(AbstractEntity $entity): bool
    {
        $this->entityManager->beginTransaction();
        try{
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return true;
        }catch(Exception $e){
            $this->entityManager->rollback();
            throw $e;
        }
    }
}