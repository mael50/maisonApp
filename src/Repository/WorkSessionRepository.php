<?php

namespace App\Repository;

use App\Entity\User;
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

    public function getDurationByMonth(User $user, \DateTimeInterface $month): int
    {
        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder->select('SUM(TIME_TO_SEC(TIMEDIFF(w.endDate, w.startDate))) as duration');
        $queryBuilder->andWhere('w.user = :user');
        $queryBuilder->andWhere('w.startDate >= :start');
        $queryBuilder->andWhere('w.endDate <= :end');
        $queryBuilder->setParameter('user', $user);
        $queryBuilder->setParameter('start', $month->format('Y-m-01 00:00:00'));
        $queryBuilder->setParameter('end', $month->format('Y-m-t 23:59:59'));

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return $result ? $result : 0;
    }

    public function findByDate(\DateTimeInterface $start, \DateTimeInterface $end, User $user): array
    {
        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder->andWhere('w.user = :user');
        $queryBuilder->andWhere('w.startDate >= :start');
        $queryBuilder->andWhere('w.startDate <= :end');
        $queryBuilder->setParameter('user', $user);
        $queryBuilder->setParameter('start', $start->format('Y-m-d 00:00:00'));
        $queryBuilder->setParameter('end', $end->format('Y-m-d 23:59:59'));

        return $queryBuilder->getQuery()->getResult();
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
