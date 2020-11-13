<?php


namespace AppBundle\Repository\Common;

use Doctrine\ORM\QueryBuilder;
use NewsBundle\Repository\NewsRepository as Base;

class NewsRepository extends Base
{
  /**
   * @return QueryBuilder
   */
  public function getPublishedAll()
  {
    $qb = $this->createQueryBuilder('n')
      ->andWhere('n.isPublished = :isPublished')
      ->orderBy('n.publishedAt', 'ASC')
      ->setParameter('isPublished', true);

    return $qb;
  }
}