<?php
/**
 * Created by PhpStorm.
 * User: eobuh
 * Date: 30.05.2018
 * Time: 11:52
 */

namespace NewsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use NewsBundle\Model\News;

class NewsRepository extends EntityRepository
{
  /**
   * @param string $order
   * @param int $limit
   * @return News[]
   */
  public function findNewsOrderByCreatedAt($limit = 5, $order = 'DESC')
  {
    $order = strtoupper($order);
    if (!in_array($order, ['ASC', 'DESC'], true))
    {
      throw new \LogicException("Order must be 'ASC' or 'DESC', not $order");
    }

    if ((int) $limit < 1 && null !== $limit)
    {
      throw new \LogicException("Limit must be greater zero, not $limit");
    }

    $qb = $this->createQueryBuilder('n')
      ->where('n.isPublished = 1');

    if (null !== $limit)
    {
      $qb->setMaxResults($limit);
    }

    return $qb->orderBy('n.publishedAt', $order)->getQuery()->getResult();
  }

  /**
   * @return int
   * @throws \Doctrine\ORM\NoResultException
   * @throws \Doctrine\ORM\NonUniqueResultException
   */
  public function countPublished()
  {
    return $this->createQueryBuilder('n')
      ->select('COUNT(n)')
      ->where('n.isPublished = 1')
      ->getQuery()->setMaxResults(1)->getSingleScalarResult();
  }
}