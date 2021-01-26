<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Repository\Company;


use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Geo\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class CompanyBranchRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, InsuranceCompanyBranch::class);
  }

  /**
   * @param InsuranceCompany $company
   * @param Region $region
   * @return mixed
   * @throws NoResultException
   * @throws NonUniqueResultException
   */
  public function findCompanyBranch(InsuranceCompany $company, Region $region)
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

  /**
   * @return \Doctrine\ORM\QueryBuilder
   */
  public function getActive()
  {
    return $this->createQueryBuilder('cb')
      ->innerJoin('cb.company', 'c')
      ->andWhere('c.published = :published')
      ->andWhere('cb.published = :published')
      ->setParameter('published', true);
  }
}
