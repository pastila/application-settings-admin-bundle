<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Model\InsuranceCompany;

use AppBundle\Entity\User\Patient;
use AppBundle\Entity\User\User;
use AppBundle\Model\Filter\PagedCollectionFilter;

class OmsChargeComplaintFilter extends PagedCollectionFilter
{
  /**
   * @var User
   */
  private $user;

  /**
   * @var Patient
   */
  private $patient;

  /**
   * @var array
   */
  private $statuses=[];

  /**
   * @var integer
   */
  private $year;

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

  /**
   * @return array
   */
  public function getStatuses ()
  {
    return $this->statuses;
  }

  /**
   * @param array $statuses
   * @return $this
   */
  public function setStatuses ($statuses)
  {
    $this->statuses = $statuses;
    return $this;
  }

  /**
   * @return int
   */
  public function getYear ()
  {
    return $this->year;
  }

  /**
   * @param int $year
   * @return $this
   */
  public function setYear ($year)
  {
    $this->year = $year;
    return $this;
  }

  /**
   * @return Patient
   */
  public function getPatient ()
  {
    return $this->patient;
  }

  /**
   * @param Patient $patient
   * @return $this
   */
  public function setPatient ($patient)
  {
    $this->patient = $patient;
    return $this;
  }
}
