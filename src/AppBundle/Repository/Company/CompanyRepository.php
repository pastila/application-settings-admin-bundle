<?php

namespace AppBundle\Repository\Company;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyStatus;
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

  public function countActiveAll()
  {
    return $this
      ->createQueryBuilder('c')
      ->select('COUNT(c)')
      ->andWhere('c.status = :status')
      ->setParameter('status', CompanyStatus::ACTIVE)
      ->getQuery()
      ->getSingleScalarResult();
  }
}
