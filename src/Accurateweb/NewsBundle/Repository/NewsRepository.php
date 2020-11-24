<?php

namespace Accurateweb\NewsBundle\Repository;

use Accurateweb\NewsBundle\Model\NewsInterface;
use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository implements NewsRepositoryInterface
{
  /**
   * Возвращает QB для получения списка новостей, отсортированных от старых к новым
   *
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getNewsListQb()
  {
    return $this
      ->createQueryBuilder('n')
      ->where('n.isPublished = 1')
      ->orderBy('n.publishedAt', 'DESC');
  }

  /**
   * @param string $order
   * @param int $limit
   * @return NewsInterface[]
   */
  public function findNewsOrderByPublishedAt($limit = 5, $order = 'DESC')
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

    $qb = $this->createQueryBuilder('n')->where('n.isPublished = 1');

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