<?php

namespace App\Repository;

use App\Entity\RecurringTasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecurringTasks>
 *
 * @method RecurringTasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecurringTasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecurringTasks[]    findAll()
 * @method RecurringTasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecurringTasksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecurringTasks::class);
    }

//    /**
//     * @return RecurringTasks[] Returns an array of RecurringTasks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RecurringTasks
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
