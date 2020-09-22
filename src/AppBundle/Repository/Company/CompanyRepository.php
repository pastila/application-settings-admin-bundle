<?php

namespace AppBundle\Repository\Company;

use AppBundle\Entity\Company\CompanyBranch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class CompanyRepository
 * @package AppBundle\Repository\Company
 */
class CompanyRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, CompanyBranch::class);
  }
}
