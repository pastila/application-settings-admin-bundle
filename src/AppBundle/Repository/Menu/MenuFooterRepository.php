<?php

namespace AppBundle\Repository\Menu;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class MenuFooterRepository
 * @package AppBundle\Repository\Menu
 */
class MenuFooterRepository extends SortableRepository
{
  /**
   * @return QueryBuilder
   */
  public function getQueryAllByPosition()
  {
    $qb = $this->createQueryBuilder('mf')
      ->orderBy('mf.position', 'ASC');

    return $qb;
  }
}
