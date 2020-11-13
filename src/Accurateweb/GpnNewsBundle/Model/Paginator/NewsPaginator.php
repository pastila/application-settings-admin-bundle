<?php


namespace Accurateweb\GpnNewsBundle\Model\Paginator;

use Doctrine\ORM\Tools\Pagination\Paginator;

class NewsPaginator extends Paginator
{
  /**
   * @param int $limit
   * @return int
   */
  public function getMaxPage($limit)
  {
    return (int) ceil($this->count() / $limit);
  }

  /**
   * @param int $limit
   * @param int $page
   * @return float|int
   */
  public function getRemainder($limit, $page)
  {
    $count = $this->count();
    $reminder = $limit > ($count - $limit * $page) ? $count - $limit * $page : $limit;
    return $reminder > 0 ? $reminder : 0;
  }

}