<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 *
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }
    public function findByIdSup($value): array
    {
        return $this->createQueryBuilder('w')
                    ->andWhere('w.id > :val')
                    ->setParameter('val', $value)
                    ->orderBy('w.id', 'ASC')
                    ->setMaxResults(10)
                    ->getQuery()
                    ->getResult();
      }

    public function findByCat($cat): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.category = :val')
            ->setParameter('val', $cat)
            ->orderBy('w.dateCreated', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findByDateRecentAncien(): array
    {
        return $this->createQueryBuilder('w')
            ->orderBy('w.dateCreated', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }
    public function findByCategorie(): array
    {
        return $this->createQueryBuilder('w')
            ->orderBy('w.category', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    //    /**
//     * @return Wish[] Returns an array of Wish objects
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

//    public function findOneBySomeField($value): ?Wish
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
