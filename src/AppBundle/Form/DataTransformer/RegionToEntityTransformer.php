<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Company\InsuranceCompanyBranch;
use Symfony\Component\Form\DataTransformerInterface;

class RegionToEntityTransformer implements DataTransformerInterface
{
  /**
   * @var InsuranceCompanyBranch
   */
  private $branch;

  public function __construct($branch)
  {
    $this->branch = $branch;
  }

  public function transform($data)
  {
    return $data;
  }

  public function reverseTransform($data)
  {
    return $this->branch->getRegion();
  }
}
