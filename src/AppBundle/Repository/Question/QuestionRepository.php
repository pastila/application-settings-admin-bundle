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
  public function getAll()
  {
    $qb = $this->createQueryBuilder('q')
      ->orderBy('q.position', 'ASC');

    return $qb;
  }
}
