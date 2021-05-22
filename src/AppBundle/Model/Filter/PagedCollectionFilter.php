<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\Filter;


use AppBundle\Model\InsuranceCompany\FeedbackListFilter;

class PagedCollectionFilter
{
  private $page;

  /**
   * @return mixed
   */
  public function getPage()
  {
    return $this->page;
  }

  /**
   * @param mixed $page
   * @return FeedbackListFilter
   */
  public function setPage($page)
  {
    $this->page = $page;
    return $this;
  }
}
