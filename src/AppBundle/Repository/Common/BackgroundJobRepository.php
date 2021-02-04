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

namespace AppBundle\Repository\Common;

use Accurateweb\TaskSchedulerBundle\Model\BackgroundJob;
use Accurateweb\TaskSchedulerBundle\Model\BackgroundJobFilter;
use Accurateweb\TaskSchedulerBundle\Model\BackgroundJobRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class BackgroundJobRepository extends EntityRepository implements BackgroundJobRepositoryInterface
{
  public function findByBackgroundJobFilter (BackgroundJobFilter $filter)
  {
    $qb = $this->createQueryBuilder('b');

    if ($filter->getClsid())
    {
      $qb->where('b.clsid = :clsid')
        ->setParameter('clsid', $filter->getClsid());
    }

    if ($filter->getState())
    {
      $qb
        ->andWhere('b.state IN (:states)')
        ->setParameter('states', $filter->getState());
    }

    return $qb->getQuery()->getResult();
  }
}