<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Validator\Constraints\PhoneVerificationAwareInterface;
use AppBundle\Validator\Constraints\PhoneVerificationRequest;
use AppBundle\Validator\User\Phone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\AttributeOverride;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\Column;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="s_users")
 * @AttributeOverrides({
 *      @AttributeOverride(name="usernameCanonical",
 *          column=@Column(
 *              nullable = true,
 *          )
 *      ),
 *      @AttributeOverride(name="username",
 *          column=@Column(
 *              nullable = true,
 *          )
 *      )
 * })
 *
 * Валидиация проверочного кода выполняется только при регистрации
 * @PhoneVerificationRequest(groups={"VerificationPhone"})
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Такой Email адрес уже зарегистрирован на сайте",
 *     groups={"RegistrationBezbahil"}
 * )
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
  private $termsAndConditionsAccepted=false;

  /**
   * @var string
   * @ORM\Column(type="string", length=50, nullable=true)
   * @Phone(message="Неверный формат телефона")
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
   * @var string
   */
  private $phoneVerificationCode;

  /**
   * Пациенты, законным предствателем которого является пользователь
   * @var Patient[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\User\Patient", mappedBy="user")
   */
  private $patients;

  /**
   * Пациент, которым является сам пользователь
   * @var Patient|null
   * @ORM\OneToOne(targetEntity="AppBundle\Entity\User\Patient")
   * @ORM\JoinColumn(nullable=true, onDelete="RESTRICT")
   */
  private $mainPatient;

  /**
   * @var Feedback[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\Feedback", mappedBy="author")
   */
  private $feedbacks;

  /**
   * User constructor.
   */
  public function __construct()
  {
    parent::__construct();
    $this->patients = new ArrayCollection();
    $this->feedbacks = new ArrayCollection();
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
  public function isTermsAndConditionsAccepted()
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
    $fullName = $this->getFullName();
    return $this->id ? ($fullName ?: $this->getEmail()) : 'Новый пользователь';
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

  /**
   * @param string $phoneVerificationCode
   */
  public function setVerificationCode($phoneVerificationCode)
  {
    $this->phoneVerificationCode = $phoneVerificationCode;
  }

  /**
   * {@inheritdoc}
   */
  public function getUsername()
  {
    return $this->email;
  }

  /**
   * {@inheritdoc}
   */
  public function getUsernameCanonical()
  {
    return $this->emailCanonical;
  }

  /**
   * {@inheritdoc}
   */
  public function setUsername($username)
  {
    $this->email = $username;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setUsernameCanonical($usernameCanonical)
  {
    $this->emailCanonical = $usernameCanonical;

    return $this;
  }

  /**
   * @return Patient[]|ArrayCollection
   */
  public function getPatients ()
  {
    return $this->patients;
  }

  /**
   * @return Patient|null
   */
  public function getMainPatient ()
  {
    return $this->mainPatient;
  }

  /**
   * @param Patient|null $mainPatient
   * @return $this
   */
  public function setMainPatient ($mainPatient)
  {
    $this->mainPatient = $mainPatient;
    return $this;
  }

  public function isAdmin ()
  {
    return $this->hasRole(self::ROLE_ADMIN);
  }

  public function setAdmin ($admin)
  {
    if ($admin)
    {
      $this->addRole(self::ROLE_ADMIN);
    }
    else
    {
      $this->removeRole(self::ROLE_ADMIN);
    }
  }

  public function getFio ()
  {
    return implode(' ',array_filter([
      $this->getLastName(),
      $this->getFirstName(),
      $this->getMiddleName(),
    ]));
  }
}
