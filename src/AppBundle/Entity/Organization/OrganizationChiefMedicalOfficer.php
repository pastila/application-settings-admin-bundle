<?php

namespace AppBundle\Entity\Organization;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationChiefMedicalOfficer.
 *
 * @ORM\Table(name="s_organisation_chief_medical_officers")
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
   * OrganizationChiefMedicalOfficer constructor.
   */
  public function __construct()
  {
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
  public function getLastName(): string
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
  public function getFirstName(): string
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
  public function getMiddleName(): string
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
    return $this->id ? $this->getFullName() : 'новый глав.врач';
  }
}
