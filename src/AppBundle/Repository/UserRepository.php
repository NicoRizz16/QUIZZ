<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    const NUM_BY_LIST_ADMIN = 50;

    public function getUserRank(User $user)
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.points > :points')
            ->setParameter('points', $user->getPoints())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getTop100()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.points', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getUsersByPage($page)
    {
        $nbPerPage = UserRepository::NUM_BY_LIST_ADMIN;

        $query = $this->createQueryBuilder('u')
            ->orderBy('u.username', 'ASC')
            ->getQuery();

        $query->setFirstResult(($page-1)*$nbPerPage)
            ->setMaxResults($nbPerPage)
        ;

        return new Paginator($query, true);
    }
}
