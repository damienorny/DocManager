<?php

namespace DocManager\DocumentBundle\Entity;

use DocManager\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
/**
 * DocumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentRepository extends EntityRepository
{
    public function getOutOfDateDocuments(User $user = null)
    {
        $qb = $this->createQueryBuilder('d');
        if(isset($user))
        {
            $qb->where('d.expirationDate < :date')
                ->setParameter('date', new \DateTime());
            $qb->andWhere('d.user = :user')
                ->setParameter('user', $user);
        }
        else
        {
            $qb->where('d.expirationDate =   :date')
                ->setParameter('date', new \DateTime());
        }
        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    public function getOutOfDateDocuments30Days()
    {
        $date = new \DateTime();
        $date->modify("-30 days");
        $qb = $this->createQueryBuilder('d');
        $qb->where('d.expirationDate < :date')
            ->setParameter('date', $date);
        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}
