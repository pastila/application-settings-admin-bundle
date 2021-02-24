<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Validator\Constraints\PhoneVerificationAwareInterface;
use AppBundle\Validator\Constraints\PhoneVerificationRequest;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;

/**
 * @ORM\Entity()
 * @ORM\Table(name="s_users")
 *
 * Валидиация проверочного кода выполняется только при регистрации
 *
 * @PhoneVerificationRequest(groups={"registration"})
 */
class User extends BaseUser implements PhoneVerificationAwareInterface
{
  const ROLE_USER = 'ROLE_USER';
  const ROLE_ADMIN = 'ROLE_ADMIN';
  const ROLE_IC_REPRESENTATIVE = 'ROLE_IC_REPRESENTATIVE';

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
   * Флаг, что пользователь представитель отделения в компании
   * @var boolean
   * @ORM\Column(type="boolean", nullable=true, options={"default"=false})
   */
  private $representative;

  /**
   * Отделение, в котором пользователь является представителем
   * @var null|InsuranceCompanyBranch
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\InsuranceCompanyBranch")
   * @ORM\JoinColumn(name="branch_id", nullable=true, onDelete="RESTRICT")
   */
  private $branch;

  /**
   * Согласие с пользовательским соглашением и обработкой персональных данных
   * При судебных разбирательствах, будет одним из доказательств для проверяющих лиц
   * @var boolean
   * @ORM\Column(type="boolean", nullable=false, options={"default"=false})
   */
  private $termsAndConditionsAccepted;

  /**
   * @var string
   * @ORM\Column(type="string", length=50, nullable=true)
   */
  private $phone;

  /**
   * @var \DateTime
   * @ORM\Column(name="birthdate", type="date", nullable=true)
   */
  private $birthDate;

  /**
   * @var string
   * @ORM\Column(type="string", length=16, nullable=true)
   */
  private $insurancePolicyNumber;


  private $phoneVerificationCode;

  /**
   * User constructor.
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param $id
   * @return $this
   */
  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

  /**
   * @return null|Region
   */
  public function getRegion()
  {
    return $this->getBranch() ? $this->getBranch()->getRegion() : null;
  }

  /**
   * @return InsuranceCompany|null
   */
  public function getCompany()
  {
    return $this->getBranch() ? $this->getBranch()->getCompany() : null;
  }

  /**
   * @return string
   */
  public function getLogin()
  {
    return $this->username;
  }

  /**
   * @param string $login
   *
   * @return $this
   */
  public function setLogin($login)
  {
    $this->username = $login;

    return $this;
  }

  /**
   * @return string
   */
  public function getFirstName()
  {
    return $this->firstName;
  }

  /**
   * @param string $firstName
   *
   * @return $this
   */
  public function setFirstName($firstName)
  {
    $this->firstName = $firstName;

    return $this;
  }

  /**
   * @return string
   */
  public function getLastName()
  {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   *
   * @return $this
   */
  public function setLastName($lastName)
  {
    $this->lastName = $lastName;

    return $this;
  }

  /**
   * @return string
   */
  public function getMiddleName()
  {
    return $this->middleName;
  }

  /**
   * @param string $middleName
   *
   * @return $this
   */
  public function setMiddleName($middleName)
  {
    $this->middleName = $middleName;

    return $this;
  }

  /**
   * @return string
   */
  public function getFullName()
  {
    $resultParts = [];
    foreach ([$this->lastName, $this->firstName, $this->middleName] as $namePart)
    {
      if ($namePart)
      {
        $resultParts[] = $namePart;
      }
    }
    return implode(' ', $resultParts);
  }

  /**
   * @return string
   */
  public function getFirstLastName()
  {
    $resultParts = [];
    foreach ([$this->firstName, $this->lastName] as $namePart)
    {
      if ($namePart)
      {
        $resultParts[] = $namePart;
      }
    }
    return implode(' ', $resultParts);
  }

  /**
   * @return bool
   */
  public function isRepresentative()
  {
    return $this->representative;
  }

  /**
   * @param $representative
   * @return $this
   */
  public function setRepresentative($representative)
  {
    $this->representative = $representative;

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
  public function getBirthDate()
  {
    return $this->birthDate;
  }

  /**
   * @param \DateTime $birthDate
   */
  public function setBirthDate($birthDate)
  {
    $this->birthDate = $birthDate;
  }

  /**
   * @return string
   */
  public function getInsurancePolicyNumber()
  {
    return $this->insurancePolicyNumber;
  }

  /**
   * @param string $insurancePolicyNumber
   */
  public function setInsurancePolicyNumber($insurancePolicyNumber)
  {
    $this->insurancePolicyNumber = $insurancePolicyNumber;
  }

  /**
   * @return bool
   */
  public function termsAndConditionsAccepted()
  {
    return $this->termsAndConditionsAccepted;
  }

  /**
   * @param bool $termsAndConditionsAccepted
   */
  public function setTermsAndConditionsAccepted($termsAndConditionsAccepted)
  {
    $this->termsAndConditionsAccepted = $termsAndConditionsAccepted;
  }

  /**
   * @return null|InsuranceCompanyBranch
   */
  public function getBranch()
  {
    return $this->branch;
  }

  /**
   * @param InsuranceCompanyBranch $branch
   *
   * @return $this
   */
  public function setBranch($branch)
  {
    $this->branch = $branch;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->getFullName() : 'Новый пользователь';
  }

  /**
   * @return mixed
   */
  public function getVerifiedPhone()
  {
    return $this->phone;
  }

  /**
   * @return mixed
   */
  public function getVerificationCode()
  {
    return $this->phoneVerificationCode;
  }


}
