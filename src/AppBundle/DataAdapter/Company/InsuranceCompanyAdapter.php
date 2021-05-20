<?php

namespace AppBundle\DataAdapter\Company;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use AppBundle\Entity\Company\InsuranceCompany;

class InsuranceCompanyAdapter implements ClientApplicationModelAdapterInterface
{
  /**
   * @param InsuranceCompany $subject
   * @param array $options
   * @return array
   */
  public function transform ($subject, $options = array())
  {
    return [
      'id' => $subject->getId(),
      'name' => $subject->getName(),
    ];
  }

  public function getModelName ()
  {
    return 'InsuranceCompany';
  }

  public function supports ($subject)
  {
    return $subject instanceof InsuranceCompany;
  }

  public function getName ()
  {
    return 'insurance_company';
  }

}