<?php

namespace AppBundle\Model\Filter;

use AppBundle\Entity\User\User;

class PatientFilter
{
  /**
   * @var string
   */
  private $lastName;

  /**
   * @var string
   */
  private $firstName;

  /**
   * @var string
   */
  private $middleName;

  /**
   * @var string
   */
  private $insurancePolicyNumber;

  /**
   * @var User
   */
  private $user;

  /**
   * @return string
   */
  public function getLastName ()
  {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   * @return $this
   */
  public function setLastName ($lastName)
  {
    $this->lastName = $lastName;
    return $this;
  }

  /**
   * @return string
   */
  public function getFirstName ()
  {
    return $this->firstName;
  }

  /**
   * @param string $firstName
   * @return $this
   */
  public function setFirstName ($firstName)
  {
    $this->firstName = $firstName;
    return $this;
  }

  /**
   * @return string
   */
  public function getMiddleName ()
  {
    return $this->middleName;
  }

  /**
   * @param string $middleName
   * @return $this
   */
  public function setMiddleName ($middleName)
  {
    $this->middleName = $middleName;
    return $this;
  }

  /**
   * @return string
   */
  public function getInsurancePolicyNumber ()
  {
    return $this->insurancePolicyNumber;
  }

  /**
   * @param string $insurancePolicyNumber
   * @return $this
   */
  public function setInsurancePolicyNumber ($insurancePolicyNumber)
  {
    $this->insurancePolicyNumber = $insurancePolicyNumber;
    return $this;
  }

  /**
   * @return User
   */
  public function getUser ()
  {
    return $this->user;
  }

  /**
   * @param User $user
   * @return $this
   */
  public function setUser ($user)
  {
    $this->user = $user;
    return $this;
  }
}