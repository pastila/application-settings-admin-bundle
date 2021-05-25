<?php
/**
 *  (c) 2019 ИП Рагозин Денис Николаевич. Все права защищены.
 *
 *  Настоящий файл является частью программного продукта, разработанного ИП Рагозиным Денисом Николаевичем
 *  (ОГРНИП 315668300000095, ИНН 660902635476).
 *
 *  Алгоритм и исходные коды программного кода программного продукта являются коммерческой тайной
 *  ИП Рагозина Денис Николаевича. Любое их использование без согласия ИП Рагозина Денис Николаевича рассматривается,
 *  как нарушение его авторских прав.
 *   Ответственность за нарушение авторских прав наступает в соответствии с действующим законодательством РФ.
 */

namespace AppBundle\Model;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */
class Pagination implements \Countable, \IteratorAggregate
{
  /**
   * @var integer
   */
  private $page;

  /**
   * @var integer
   */
  private $maxPerPage;

  /**
   * @var integer
   */
  private $pageCount;

  /**
   * @var integer
   */
  private $nbResults;

  /**
   * @var Paginator
   */
  private $paginator;

  public function __construct(QueryBuilder $queryBuilder, $page, $maxPerPage)
  {
    $this->page = $page;
    $this->maxPerPage = $maxPerPage;

    $pagedQueryBuilder = clone $queryBuilder
                                  ->setFirstResult($this->getOffset())
                                  ->setMaxResults($this->maxPerPage);

    $this->paginator = new Paginator($pagedQueryBuilder, false);

    $this->nbResults = count($this->paginator);
    $this->pageCount = ceil($this->nbResults / $this->maxPerPage);
  }

  public function getIterator()
  {
    return $this->paginator->getIterator();
  }

  /**
   * @return int
   */
  public function getLastPage()
  {
    return $this->pageCount;
  }

  /**
   * @return int
   */
  public function getOffset()
  {
    return ($this->page - 1) * $this->maxPerPage;
  }

  /**
   * @return int
   */
  public function getPage()
  {
    return $this->page;
  }

  /**
   * @return int
   */
  public function getMaxPerPage()
  {
    return $this->maxPerPage;
  }

  /**
   * @return int
   */
  public function getPageCount()
  {
    return $this->pageCount;
  }

  /**
   * @return int
   */
  public function getNbResults()
  {
    return $this->nbResults;
  }

  /**
   * @return int
   */
  public function getFirstResult()
  {
    return $this->getOffset() + 1;
  }

  /**
   * @return integer
   */
  public function getLastResult()
  {
    return min($this->getNbResults(), $this->getFirstResult() + $this->getMaxPerPage() - 1);
  }

  /**
   * @return int
   */
  public function getMore ()
  {
    if ($this->getLastPage() == $this->getPage())
    {
      return 0;
    }

    return min($this->getMaxPerPage(), max($this->getNbResults() - ($this->getPage() * $this->getMaxPerPage()), 0));
  }

  public function count ()
  {
    return $this->paginator->count();
  }
}
