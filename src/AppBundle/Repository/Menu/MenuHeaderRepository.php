<?php

namespace AppBundle\Repository\Menu;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class MenuHeaderRepository
 * @package AppBundle\Repository\Menu
 */
class MenuHeaderRepository extends SortableRepository
{
  /**
   * @return QueryBuilder
   */
  public function getAll()
  {
    $qb = $this->createQueryBuilder('mh')
      ->orderBy('mh.position', 'ASC');

    return $qb;
  }
}
