<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Repository\Company\InsuranceCompanyBranchRepository;
use Doctrine\ORM\NoResultException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MedicalCompanyExtension extends AbstractExtension
{
  private $branchRepository;

  public function __construct (InsuranceCompanyBranchRepository $branchRepository)
  {
    $this->branchRepository = $branchRepository;
  }

  public function getFunctions ()
  {
    return [
      new TwigFunction('resolveBranchByInsuranceCompanyAndRegion', [$this, 'resolveBranchByInsuranceCompanyAndRegion']),
    ];
  }

  /**
   * @param InsuranceCompany $company
   * @param Region $region
   * @return InsuranceCompanyBranch|null
   */
  public function resolveBranchByInsuranceCompanyAndRegion (InsuranceCompany $company, Region $region)
  {
    return $this->branchRepository->findOnePublishedByCompanyAndRegion($company, $region);
  }
}