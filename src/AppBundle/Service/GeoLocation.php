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

namespace AppBundle\Service;


use Accurateweb\LocationBundle\GeoLocation\GeoInterface;
use AppBundle\Repository\Geo\RegionRepository;
use Complex\Exception;

class GeoLocation
{
  private $geo;
  private $regionRepository;

  public function __construct(
    GeoInterface $geo,
    RegionRepository $regionRepository
  )
  {
    $this->geo = $geo;
    $this->regionRepository = $regionRepository;
  }

  /**
   * @return mixed
   * @throws \Doctrine\ORM\NoResultException
   * @throws \Doctrine\ORM\NonUniqueResultException
   */
  public function getRegion()
  {
    $codes = $this->geo->getRegionCodes();
    $codes = explode(',', $codes);
    $codes = array_map(function ($n)
    {
      return trim($n);
    }, $codes);

    return $this->regionRepository
      ->createQueryBuilder('r')
      ->andWhere('r.code IN (:codes)')
      ->setParameter('codes', $codes)
      ->getQuery()
      ->setMaxResults(1)
      ->getFirstResult();
  }
}