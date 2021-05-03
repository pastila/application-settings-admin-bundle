<?php

namespace AppBundle\Repository\Organization;

use AppBundle\Entity\Organization\MedicalOrganization;
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
    parent::__construct($registry, MedicalOrganization::class);
  }
}
