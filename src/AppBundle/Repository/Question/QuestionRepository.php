<?php

namespace AppBundle\Repository\Question;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class QuestionRepository
 * @package AppBundle\Repository\Question
 */
class QuestionRepository extends SortableRepository
{
  /**
   * @return QueryBuilder
   */
  public function getQueryAllSortByPosition()
  {
    $qb = $this->createQueryBuilder('q')
      ->orderBy('q.position', 'ASC');

    return $qb;
  }

  /**
   * @return mixed
   * @throws \Doctrine\ORM\NoResultException
   * @throws \Doctrine\ORM\NonUniqueResultException
   */
  public function getCount()
  {
    return $this->createQueryBuilder('q')
      ->select('count(q)')
      ->getQuery()
      ->getSingleScalarResult();
  }
}
