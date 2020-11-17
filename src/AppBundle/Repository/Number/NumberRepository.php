<?php

namespace AppBundle\Repository\Number;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class NumberRepository
 * @package AppBundle\Repository\Number
 */
class NumberRepository extends SortableRepository
{
  /**
   * @return QueryBuilder
   */
  public function getAll()
  {
    $qb = $this->createQueryBuilder('nm')
      ->orderBy('nm.position', 'ASC');

    return $qb;
  }
}
