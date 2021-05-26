<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\Filter;


use AppBundle\Model\InsuranceCompany\FeedbackListFilter;

class PagedCollectionFilter
{
  const DEFAULT_PER_PAGE = 10;
  /**
   * @var integer
   */
  private $page;

  /**
   * @var int
   */
  private $perPage = self::DEFAULT_PER_PAGE;

  /**
   * @return integer
   */
  public function getPage()
  {
    return $this->page;
  }

  /**
   * @param integer $page
   * @return $this
   */
  public function setPage($page)
  {
    $this->page = $page;
    return $this;
  }

  /**
   * @return int
   */
  public function getPerPage ()
  {
    return $this->perPage;
  }

  /**
   * @param int $perPage
   * @return $this
   */
  public function setPerPage ($perPage)
  {
    $this->perPage = $perPage;
    return $this;
  }
}
