<?php

namespace App\Repository;

use App\Entity\WorkSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkSession>
 *
 * @method WorkSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkSession[]    findAll()
 * @method WorkSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkSession::class);
    }

//    /**
//     * @return WorkSession[] Returns an array of WorkSession objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorkSession
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
