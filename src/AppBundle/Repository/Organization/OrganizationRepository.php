<?php

namespace AppBundle\Repository\Organization;

use AppBundle\Entity\Organization\Organization;
use AppBundle\Entity\Organization\OrganizationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class OrganizationRepository
 * @package AppBundle\Repository\Organization
 */
class OrganizationRepository extends ServiceEntityRepository
{
  /**
   * OrganizationRepository constructor.
   * @param ManagerRegistry $registry
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Organization::class);
  }

  /**
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getQueryActive()
  {
    return $this->createQueryBuilder('o')
      ->andWhere('o.status = :status')
      ->setParameter('status', OrganizationStatus::ACTIVE);
  }
}
