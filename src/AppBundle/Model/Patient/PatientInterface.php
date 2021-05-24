<?php

namespace AppBundle\Model\Patient;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Geo\Region;

interface PatientInterface
{
  /**
   * @return string
   */
  public function getFirstName ();

  /**
   * @param string $firstName
   * @return $this
   */
  public function setFirstName ($firstName);

  /**
   * @return string
   */
  public function getLastName ();

  /**
   * @param string $lastName
   * @return $this
   */
  public function setLastName ($lastName);

  /**
   * @return string
   */
  public function getMiddleName ();

  /**
   * @param string $middleName
   * @return $this
   */
  public function setMiddleName ($middleName);

  /**
   * @return string
   */
  public function getPhone ();

  /**
   * @param string $phone
   * @return $this
   */
  public function setPhone ($phone);

  /**
   * @return \DateTime
   */
  public function getBirthDate ();

  /**
   * @param \DateTime $birthDate
   * @return $this
   */
  public function setBirthDate ($birthDate);

  /**
   * @return string
   */
  public function getInsurancePolicyNumber ();

  /**
   * @param string $insurancePolicyNumber
   * @return $this
   */
  public function setInsurancePolicyNumber ($insurancePolicyNumber);

  /**
   * @return InsuranceCompany
   */
  public function getInsuranceCompany ();

  /**
   * @param InsuranceCompany $insuranceCompany
   * @return $this
   */
  public function setInsuranceCompany ($insuranceCompany);

  /**
   * @return Region
   */
  public function getRegion ();

  /**
   * @param Region $region
   * @return $this
   */
  public function setRegion ($region);
}