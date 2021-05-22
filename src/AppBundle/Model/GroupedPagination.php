<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model;

use Doctrine\ORM\QueryBuilder;

class GroupedPagination extends Pagination
{
  private $lastItemOnPreviousPageQb;

  public function __construct(QueryBuilder $queryBuilder, $page, $maxPerPage)
  {
    parent::__construct($queryBuilder, $page, $maxPerPage);

    if ($this->getOffset() > 1)
    {
      $this->lastItemOnPreviousPageQb = clone $queryBuilder
        ->setFirstResult($this->getOffset() - 1)
        ->setMaxResults(1);
    }
  }

  public function getLastItemOnPreviousPage()
  {
    if ($this->lastItemOnPreviousPageQb)
    {
      return $this->lastItemOnPreviousPageQb->getQuery()->getOneOrNullResult();
    }

    return null;
  }
}
