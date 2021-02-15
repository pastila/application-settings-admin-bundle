<?php

namespace AppBundle\Entity\Organization;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * OrganizationChiefMedicalOfficer.
 *
 * @ORM\Table(name="s_organization_chief_medical_officers")
 * @ORM\Entity()
 */
class OrganizationChiefMedicalOfficer
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * МО
   *
   * @OneToOne(targetEntity="Organization", inversedBy="chiefMedicalOfficer", cascade={"persist", "remove"})
   * @JoinColumn(name="organization_id", referencedColumnName="code")
   */
  private $organization;

  /**
   * Фамилия
   *
   * @var string
   *
   * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
   */
  private $lastName;

  /**
   * Имя
   *
   * @var string
   *
   * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
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
   * OrganizationChiefMedicalOfficer constructor.
   */
  public function __construct()
  {
  }

  /**
   * @return mixed
   */
  public function getOrganization()
  {
    return $this->organization;
  }

  /**
   * @param mixed $organization
   */
  public function setOrganization($organization): void
  {
    $this->organization = $organization;
  }

  /**
   * @return int
   */
  public function getId(): int
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
    return $this->id ? $this->getFullName() : 'Новый глав.врач';
  }
}
