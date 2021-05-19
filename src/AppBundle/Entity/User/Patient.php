<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Entity\User;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Validator\Constraints\PhoneVerificationAwareInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\User\PatientRepository")
 * @ORM\Table(name="patients")
 */
class Patient implements PhoneVerificationAwareInterface
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * @var string
   * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
   */
  protected $firstName;

  /**
   * @var string
   * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
   */
  protected $lastName;

  /**
   * @var string
   * @ORM\Column(name="middle_name", type="string", length=255, nullable=true)
   */
  protected $middleName;

  /**
   * @var string
   * @ORM\Column(type="string", length=50, nullable=true)
   */
  private $phone;

  /**
   * @var \DateTime
   * @ORM\Column(type="date", nullable=true)
   */
  private $birthDate;

  /**
   * @var string
   * @ORM\Column(type="string", length=16, nullable=true)
   */
  private $insurancePolicyNumber;

  /**
   * @var InsuranceCompany
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\InsuranceCompany")
   * @ORM\JoinColumn(nullable=false)
   */
  private $insuranceCompany;

  /**
   * @var Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @ORM\JoinColumn(nullable=false)
   */
  private $region;

  /**
   * @var User
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User", inversedBy="patients")
   * @ORM\JoinColumn(nullable=false)
   */
  private $user;

  /**
   * @var string
   */
  private $phoneVerificationCode;

  /**
   * @return int
   */
  public function getId ()
  {
    return $this->id;
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
  public function getPhone ()
  {
    return $this->phone;
  }

  /**
   * @param string $phone
   * @return $this
   */
  public function setPhone ($phone)
  {
    $this->phone = $phone;
    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getBirthDate ()
  {
    return $this->birthDate;
  }

  /**
   * @param \DateTime $birthDate
   * @return $this
   */
  public function setBirthDate ($birthDate)
  {
    $this->birthDate = $birthDate;
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
   * @return InsuranceCompany
   */
  public function getInsuranceCompany ()
  {
    return $this->insuranceCompany;
  }

  /**
   * @param InsuranceCompany $insuranceCompany
   * @return $this
   */
  public function setInsuranceCompany ($insuranceCompany)
  {
    $this->insuranceCompany = $insuranceCompany;
    return $this;
  }

  /**
   * @return Region
   */
  public function getRegion ()
  {
    return $this->region;
  }

  /**
   * @param Region $region
   * @return $this
   */
  public function setRegion ($region)
  {
    $this->region = $region;
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

  /**
   * @return string
   */
  public function getFio ()
  {
    return implode(' ', array_filter([
      $this->getLastName(),
      $this->getFirstName(),
      $this->getMiddleName(),
    ]));
  }

  public function __toString ()
  {
    return $this->getFio() ? $this->getFio() : 'Новый пациент';
  }

  public function getVerifiedPhone ()
  {
    return $this->getPhone();
  }

  public function getVerificationCode ()
  {
    return $this->phoneVerificationCode;
  }

  /**
   * @param string $phoneVerificationCode
   * @return $this
   */
  public function setVerificationCode ($phoneVerificationCode)
  {
    $this->phoneVerificationCode = $phoneVerificationCode;
    return $this;
  }
}
