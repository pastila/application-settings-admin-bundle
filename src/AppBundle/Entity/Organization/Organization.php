<?php

namespace AppBundle\Entity\Organization;

use AppBundle\Entity\Geo\Region;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Organization.
 *
 * @ORM\Table(name="s_organisations")
 * @ORM\Entity()
 */
class Organization
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Название МО
   *
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=255, nullable=false)
   */
  private $name;

  /**
   * Полное название МО
   *
   * @var string
   *
   * @ORM\Column(name="name_full", type="string", length=512, nullable=true)
   */
  private $fullName;

  /**
   * Код МО
   *
   * @var string
   *
   * @ORM\Column(name="code", type="string", length=255, nullable=false, unique=true)
   */
  private $code;

  /**
   * Адрес МО
   *
   * @var string
   *
   * @ORM\Column(name="address", type="string", length=512, nullable=true)
   */
  private $address;

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
   * @var int
   * @ORM\Column(type="integer", nullable=false)
   */
  private $status;

  /**
   * Регион
   *
   * @var null|Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @ORM\JoinColumn(name="region_id", nullable=true, onDelete="RESTRICT")
   */
  private $region;

  /**
   * @var OrganizationYear[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Organization\OrganizationYear", mappedBy="organization")
   */

  /**
   * Года
   * @ManyToMany(targetEntity="OrganizationYear", inversedBy="organization")
   * @JoinTable(name="s_organizations_to_years",
   *      joinColumns={@JoinColumn(name="organization_id", referencedColumnName="id")},
   *      inverseJoinColumns={@JoinColumn(name="year_id", referencedColumnName="id")}
   *      )
   */
  protected $years;

  /**
   * Organization constructor.
   */
  public function __construct()
  {
    $this->years = new ArrayCollection();
  }

  /**
   * @return OrganizationYear[]|ArrayCollection
   */
  public function getYears()
  {
    return $this->years;
  }

  /**
   * @return Region|null
   */
  public function getRegion(): ?Region
  {
    return $this->region;
  }

  /**
   * @param Region|null $region
   */
  public function setRegion(?Region $region): void
  {
    $this->region = $region;
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return int|null
   */
  public function getBitrixId()
  {
    return $this->bitrixId;
  }

  /**
   * @param int|null $bitrixId
   * @return $this
   */
  public function setBitrixId(?int $bitrixId)
  {
    $this->bitrixId = $bitrixId;
    return $this;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   *
   * @return string
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return string
   */
  public function getFullName()
  {
    return $this->fullName;
  }

  /**
   * @param string $fullName
   */
  public function setFullName($fullName): void
  {
    $this->fullName = $fullName;
  }

  /**
   * @return string
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @param string $code
   */
  public function setCode(string $code): void
  {
    $this->code = $code;
  }

  /**
   * @return string
   */
  public function getAddress()
  {
    return $this->address;
  }

  /**
   * @param string $address
   */
  public function setAddress($address): void
  {
    $this->address = $address;
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
   * @return int
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * @return int|string
   */
  public function getStatusLabel ()
  {
    return OrganizationStatus::getName($this->status);
  }

  /**
   * @param $status
   * @return $this
   */
  public function setStatus($status)
  {
    if (null !== $status && !in_array($status, OrganizationStatus::getAvailableValues()))
    {
      throw new \InvalidArgumentException();
    }

    $this->status = $status;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->name : '';
  }

  /**
   * @param $years
   */
  public function setYears($years): void
  {
    foreach ($years as $year) {
      if (!$this->years->contains($year))
      {
        $this->years->add($year);
      }
    }
  }
}
