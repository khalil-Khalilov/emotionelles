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

}
