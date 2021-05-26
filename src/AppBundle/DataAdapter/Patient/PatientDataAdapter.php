<?php

namespace AppBundle\DataAdapter\Patient;

use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelAdapterInterface;
use AppBundle\DataAdapter\Company\InsuranceCompanyAdapter;
use AppBundle\DataAdapter\Geo\RegionDataAdapter;
use AppBundle\Model\Patient\PatientInterface;

class PatientDataAdapter implements ClientApplicationModelAdapterInterface
{
  private $regionDataAdapter;
  private $insuranceCompanyAdapter;

  public function __construct (
    RegionDataAdapter $regionDataAdapter,
    InsuranceCompanyAdapter $insuranceCompanyAdapter
  )
  {
    $this->regionDataAdapter = $regionDataAdapter;
    $this->insuranceCompanyAdapter = $insuranceCompanyAdapter;
  }

  /**
   * @param PatientInterface $subject
   * @param array $options
   * @return array
   */
  public function transform ($subject, $options = array())
  {
    return [
      'lastName' => $subject->getLastName(),
      'firstName' => $subject->getFirstName(),
      'middleName' => $subject->getMiddleName(),
      'birthDate' => $subject->getBirthDate() ? $subject->getBirthDate()->format('F j, Y H:i') : null,
      'phone' => $subject->getPhone(),
      'insurancePolicyNumber' => $subject->getInsurancePolicyNumber(),
      'region' => $subject->getRegion() ? $this->regionDataAdapter->transform($subject->getRegion()) : null,
      'insuranceCompany' => $subject->getInsuranceCompany() ? $this->insuranceCompanyAdapter->transform($subject->getInsuranceCompany()) : null,
    ];
  }

  public function getModelName ()
  {
    return 'PatientData';
  }

  public function supports ($subject)
  {
    return $subject instanceof PatientInterface;
  }

  public function getName ()
  {
    return 'patient_date';
  }

}