<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\InsuranceCompany\Branch;


use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\CompanyStatus;
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
    } catch (UnexpectedResultException $e)
    {
      return null;
    }
  }

  public function buildRating(Region $region = null)
  {
    if ($region)
    {
      return $this->buildRatingForRegion($region);
    }

    return $this->buildRatingOverall();
  }

  public function buildRatingForRegion(Region $region)
  {
    $repository = $this->companyRepository;
    return $repository
      ->getActive()
      ->innerJoin('c.branches', 'cb')
      ->select(['c', 'SUM(cb.valuation) AS actualRating'])
      ->andWhere('cb.region = :region AND cb.valuation > 0')
      ->setParameter('region', $region)
      ->orderBy('actualRating', 'DESC')
      ->groupBy('c.id')
      ->getQuery()
      ->getResult();
  }

  public function buildRatingOverall()
  {
    $repo = $this->companyRepository;
    return $repo
      ->getActive()
      ->innerJoin('c.branches', 'cb')
      ->select(['c, c.valuation AS actualRating'])
      ->andWhere('c.valuation > 0')
      ->orderBy('c.valuation', 'DESC')
      ->getQuery()
      ->getResult();
  }
}
