<?php

namespace AppBundle\Repository\Menu;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class MenuSocialRepository
 * @package AppBundle\Repository\Menu
 */
class MenuSocialRepository extends SortableRepository
{
  /**
   * @return QueryBuilder
   */
  public function getQueryAllByPosition()
  {
    $qb = $this->createQueryBuilder('mh')
      ->orderBy('mh.position', 'ASC');

    return $qb;
  }
}
