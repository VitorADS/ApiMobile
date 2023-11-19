<?php

namespace App\Repository;

use App\Entity\Despesa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Despesa>
 *
 * @method Despesa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Despesa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Despesa[]    findAll()
 * @method Despesa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DespesaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Despesa::class);
    }

//    /**
//     * @return Despesa[] Returns an array of Despesa objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Despesa
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
