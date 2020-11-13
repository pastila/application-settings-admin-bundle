<?php
/**
 * Created by PhpStorm.
 * User: eobuh
 * Date: 30.09.2019
 * Time: 12:50
 */

namespace Accurateweb\GpnNewsBundle\Repository;

use Accurateweb\GpnNewsBundle\Model\NewsInterface;

interface NewsRepositoryInterface
{
  /**
   * Возвращает QB для получения списка новостей, отсортированных от старых к новым
   *
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getNewsListQb();

  /**
   * @param string $order
   * @param int $limit
   * @return NewsInterface[]
   */
  public function findNewsOrderByPublishedAt($limit = 5, $order = 'DESC');

  /**
   * @return int
   * @throws \Doctrine\ORM\NoResultException
   * @throws \Doctrine\ORM\NonUniqueResultException
   */
  public function countPublished();
}