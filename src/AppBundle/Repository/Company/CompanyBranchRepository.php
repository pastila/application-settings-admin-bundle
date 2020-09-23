<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Repository\Company;


use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Geo\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class CompanyBranchRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, CompanyBranch::class);
  }

  /**
   * @param Company $company
   * @param Region $region
   * @return mixed
   * @throws NoResultException
   * @throws NonUniqueResultException
   */
  public function findCompanyBranch(Company $company, Region $region)
  {
      return $this
        ->createQueryBuilder('cb')
        ->where('cb.company = :company AND cb.region = :region')
        ->setParameters([
          'company' => $company,
          'region' => $region
        ])
        ->getQuery()
        ->getSingleResult();
  }
}
