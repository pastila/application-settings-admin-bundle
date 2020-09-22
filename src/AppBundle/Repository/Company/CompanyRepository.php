<?php

namespace AppBundle\Repository\Company;

use AppBundle\Entity\Company\Company;
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
    parent::__construct($registry, Company::class);
  }

  public function countAll()
  {
    return $this
      ->createQueryBuilder('c')
      ->select('COUNT(c)')
      ->getQuery()
      ->getSingleScalarResult();
  }
}
