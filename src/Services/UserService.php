<?php

namespace App\Services;

use App\Entity\AbstractEntity;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class UserService extends AbstractService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }

    /**
     * @param User $entity
     * @param ?int $id = null
     * @return AbstractEntity
     */
    public function save(AbstractEntity $entity, ?int $id = null): AbstractEntity
    {
        /** @var User $user */
        $user = $this->getRepository()->findOneBy(['email' => $entity->getEmail()]);

        if($user instanceof User && ($id === null || $user->getId() !== $id)){
            throw new Exception('E-mail ja cadastrado!', 400);
        }

        return parent::save($entity, $id);
    }
}