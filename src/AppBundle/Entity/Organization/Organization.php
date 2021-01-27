<?php

namespace AppBundle\Entity\Organization;

use AppBundle\Entity\Geo\Region;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Organization.
 *
 * @ORM\Table(name="s_organizations")
 * @ORM\Entity()
 */
class Organization
{
  /**
   * Код МО
   *
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $code;

  /**
   * Название МО
   *
   * @var string
   * @ORM\Column(name="name", type="string", length=255, nullable=false)
   */
  private $name;

  /**
   * Полное название МО
   *
   * @var string
   * @ORM\Column(name="name_full", type="string", length=512, nullable=true)
   */
  private $fullName;

  /**
   * Адрес МО
   *
   * @var string
   * @ORM\Column(name="address", type="string", length=512, nullable=true)
   */
  private $address;

  /**
   * Глав.врач
   *
   * @OneToOne(targetEntity="OrganizationChiefMedicalOfficer", mappedBy="customer")
   */
  private $chiefMedicalOfficer;

  /**
   * @var int
   * @ORM\Column(type="boolean", nullable=false, options={"default"=true})
   */
  private $published;

  /**
   * Регион
   *
   * @var Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @ORM\JoinColumn(name="region_id", nullable=false, onDelete="RESTRICT")
   */
  private $region;

  /**
   * Года
   *
   * @ManyToMany(targetEntity="OrganizationYear", inversedBy="organizations")
   * @JoinTable(name="s_organizations_to_years",
   *      joinColumns={@JoinColumn(name="organization_id", referencedColumnName="code")},
   *      inverseJoinColumns={@JoinColumn(name="year_id", referencedColumnName="year")}
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
   * @return mixed
   */
  public function getChiefMedicalOfficer()
  {
    return $this->chiefMedicalOfficer;
  }

  /**
   * @param mixed $chiefMedicalOfficer
   */
  public function setChiefMedicalOfficer($chiefMedicalOfficer): void
  {
    $this->chiefMedicalOfficer = $chiefMedicalOfficer;
  }

  /**
   * @return OrganizationYear[]|ArrayCollection
   */
  public function getYears()
  {
    return $this->years;
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
   * @return int
   */
  public function getPublished()
  {
    return $this->published;
  }

  /**
   * @return int|string
   */
  public function getStatusLabel ()
  {
    return OrganizationStatus::getName($this->published);
  }

  /**
   * @param $published
   * @return $this
   */
  public function setPublished($published)
  {
    if (null !== $published && !in_array($published, OrganizationStatus::getAvailableValues()))
    {
      throw new \InvalidArgumentException();
    }

    $this->published = $published;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->code ? $this->name : 'Новая МО';
  }
}
