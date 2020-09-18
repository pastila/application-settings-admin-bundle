<?php

namespace AppBundle\Repository\Geo;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CompanyRepository
 * @package AppBundle\Repository\Geo
 */
class RegionRepository extends ServiceEntityRepository
{
  /**
   * CompanyRepository constructor.
   * @param ManagerRegistry $registry
   * @param string $entityClass
   */
  public function __construct(ManagerRegistry $registry, $entityClass = 'AppBundle\Entity\Geo\Region')
  {
    parent::__construct($registry, $entityClass);
  }

  /**
   * @return array
   */
  public function findAvailableRegion()
  {
    return $this->findAll();
  }
}