<?php

namespace AppBundle\Repository\Geo;

use AppBundle\Entity\Geo\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @package AppBundle\Repository\Geo
 */
class RegionRepository extends ServiceEntityRepository
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
}
