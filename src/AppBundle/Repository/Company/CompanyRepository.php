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
      ->getActive()
      ->select('COUNT(c)')
      ->getQuery()
      ->getSingleScalarResult();
  }

  /**
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getActive()
  {
    return $this->createQueryBuilder('c')
      ->andWhere('c.status = :status')
      ->setParameter('status', CompanyStatus::ACTIVE);
  }

  /**
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getWithBranchActive()
  {
    return $this->getActive()
      ->innerJoin('c.branches', 'cb', 'WITH', 'cb.status = :status');
  }
}
