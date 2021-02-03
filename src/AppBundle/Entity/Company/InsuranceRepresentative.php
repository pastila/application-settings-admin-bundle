<?php

namespace AppBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * InsuranceRepresentative.
 *
 * @ORM\Table(name="s_representatives")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\InsuranceRepresentativeRepository")
 */
class InsuranceRepresentative
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Филиал
   *
   * @var null|InsuranceCompanyBranch
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\InsuranceCompanyBranch", inversedBy="representatives", cascade={"persist", "remove"})
   * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
   */
  private $branch;

  /**
   * Email
   *
   * @var string
   *
   * @ORM\Column(name="email", type="string", length=256, nullable=false)
   */
  private $email;

  /**
   * Фамилия
   *
   * @var string
   *
   * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
   */
  private $lastName;

  /**
   * Имя
   *
   * @var string
   *
   * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
   */
  private $firstName;

  /**
   * Отчество
   *
   * @var string
   *
   * @ORM\Column(name="middle_name", type="string", length=255, nullable=true)
   */
  private $middleName;

  /**
   * InsuranceRepresentative constructor.
   */
  public function __construct ()
  {
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param string $email
   */
  public function setEmail($email): void
  {
    $this->email = $email;
  }

  /**
   * @return InsuranceCompanyBranch
   */
  public function getBranch()
  {
    return $this->branch;
  }

  /**
   * @param $branch
   */
  public function setBranch($branch): void
  {
    $this->branch = $branch;
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
   */
  public function setLastName($lastName): void
  {
    $this->lastName = $lastName;
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
   */
  public function setFirstName($firstName): void
  {
    $this->firstName = $firstName;
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
   */
  public function setMiddleName($middleName): void
  {
    $this->middleName = $middleName;
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
  public function __toString()
  {
    $fullName = $this->getFullName();
    return $this->id ?  ($fullName ? $fullName : $this->email) : 'Новый представитель филиала компании';
  }
}
