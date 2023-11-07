<?php

namespace App\Repository;

use App\Entity\WorkSessionTasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkSessionTasks>
 *
 * @method WorkSessionTasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkSessionTasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkSessionTasks[]    findAll()
 * @method WorkSessionTasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkSessionTasksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkSessionTasks::class);
    }

//    /**
//     * @return WorkSessionTasks[] Returns an array of WorkSessionTasks objects
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

//    public function findOneBySomeField($value): ?WorkSessionTasks
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
