<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\InsuranceCompany\Branch;


use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Repository\Company\CompanyBranchRepository;
use Doctrine\ORM\UnexpectedResultException;

class BranchRatingHelper
{
  private $branchRepository;

  public function __construct(CompanyBranchRepository $branchRepository)
  {
    $this->branchRepository = $branchRepository;
  }

  /**
   * @param Company $company
   * @param Region|null $region
   * @return |null
   */
  public function getRating(Company $company, Region $region = null)
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
}
