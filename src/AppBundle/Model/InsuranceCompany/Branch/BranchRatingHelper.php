<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\InsuranceCompany\Branch;


use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Repository\Company\CompanyBranchRepository;
use AppBundle\Repository\Company\CompanyRepository;
use Doctrine\ORM\UnexpectedResultException;

class BranchRatingHelper
{
  private $branchRepository;

  private $companyRepository;

  public function __construct(
    CompanyBranchRepository $branchRepository,
    CompanyRepository $companyRepository)
  {
    $this->branchRepository = $branchRepository;
    $this->companyRepository = $companyRepository;
  }

  /**
   * @param Company $company
   * @param Region|null $region
   * @return |null
   */
  public function getCompanyRating(Company $company, Region $region = null)
  {
    if (!$region)
    {
      return $company->getRating();
    }

    try
    {
      return $this->branchRepository->findCompanyBranch($company, $region)->getRating();
    }
    catch (UnexpectedResultException $e)
    {
      return null;
    }
  }

  public function buildRating(Region $region=null)
  {
    if ($region)
    {
      return $this->buildRatingForRegion($region);
    }

    return $this->buildRatingOverall();
  }

  public function buildRatingForRegion(Region $region)
  {
    return $this
      ->companyRepository
      ->createQueryBuilder('c')
      ->innerJoin('c.branches', 'cb')
      ->select(['c', 'SUM(cb.valuation) AS actualRating'])
      ->where('cb.region = :region AND cb.valuation > 0')
      ->setParameter('region', $region)
      ->orderBy('actualRating', 'DESC')
      ->groupBy('c.id')
      ->getQuery()
      ->getResult();
  }

  public function buildRatingOverall()
  {
    return $this
      ->companyRepository
      ->createQueryBuilder('cb')
      ->select(['cb, cb.valuation AS actualRating'])
      ->where('cb.valuation > 0')
      ->orderBy('cb.valuation', 'DESC')
      ->getQuery()
      ->getResult();
  }
}
