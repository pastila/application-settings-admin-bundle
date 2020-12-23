<?php

namespace AppBundle\Repository\Geo;

use Accurateweb\LocationBundle\Model\ResolvedUserLocation;
use Accurateweb\LocationBundle\Model\UserLocationRepositoryInterface;
use AppBundle\Entity\Geo\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @package AppBundle\Repository\Geo
 */
class RegionRepository extends ServiceEntityRepository implements UserLocationRepositoryInterface
{
  /**
   * CompanyRepository constructor.
   * @param ManagerRegistry $registry
   * @param string $entityClass
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Region::class);
  }

  public function findByResolvedLocation(ResolvedUserLocation $resolvedUserLocation)
  {
    if (!$resolvedUserLocation->getRegionName())
    {
      return null;
    }

    return $this->createQueryBuilder('r')
      ->where('r.name LIKE :n')
      ->setParameter('n', '%'.$resolvedUserLocation->getRegionName().'%')
      ->getQuery()
      ->setMaxResults(1)
      ->getOneOrNullResult();
  }

  /**
   * @param $name
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function findByNameQueryBuilder($name)
  {
    $q = $this->createQueryBuilder('r');
    if ($name)
    {
      return $q
        ->andWhere('r.name LIKE :name')
        ->setParameter('name', '%' . $name . '%');
    }
    return $q;
  }
}
