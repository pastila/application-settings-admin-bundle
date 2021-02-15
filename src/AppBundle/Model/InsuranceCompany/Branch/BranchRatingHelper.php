<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\InsuranceCompany\Branch;


use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Geo\Region;
use AppBundle\Repository\Company\InsuranceCompanyBranchRepository;
use AppBundle\Repository\Company\InsuranceCompanyRepository;
use Doctrine\ORM\UnexpectedResultException;

class BranchRatingHelper
{
  private $branchRepository;

  private $companyRepository;

  public function __construct(
    InsuranceCompanyBranchRepository $branchRepository,
    InsuranceCompanyRepository $companyRepository)
  {
    $this->branchRepository = $branchRepository;
    $this->companyRepository = $companyRepository;
  }

  /**
   * @param InsuranceCompany $company
   * @param Region|null $region
   * @return |null
   */
  public function getCompanyRating(InsuranceCompany $company, Region $region = null)
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
      ->getWithBranchActive()
      ->select(['c', 'SUM(cb.valuation) AS actualRating'])
      ->andWhere('cb.region = :region AND cb.valuation > 0')
      ->setParameter('region', $region)
      ->addOrderBy('actualRating', 'DESC')
      ->addOrderBy('c.name', 'ASC')
      ->groupBy('c.id')
      ->getQuery()
      ->getResult();
  }

  public function buildRatingOverall()
  {
    $repo = $this->companyRepository;
    return $repo
      ->getWithBranchActive()
      ->select(['c, c.valuation AS actualRating'])
      ->andWhere('c.valuation > 0')
      ->addOrderBy('actualRating', 'DESC')
      ->addOrderBy('c.name', 'ASC')
      ->getQuery()
      ->getResult();
  }
}
