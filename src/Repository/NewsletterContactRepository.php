<?php

namespace App\Repository;

use App\Entity\NewsletterContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsletterContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterContact[]    findAll()
 * @method NewsletterContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterContact::class);
    }

    // /**
    //  * @return NewsletterContact[] Returns an array of NewsletterContact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NewsletterContact
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
