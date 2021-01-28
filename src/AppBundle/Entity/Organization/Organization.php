<?php

namespace AppBundle\Entity\Organization;

use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Helper\Year\Year;
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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Organization\OrganizationRepository")
 */
class Organization
{
  /**
   * Код МО
   *
   * @var integer
   * @ORM\Id()
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
   * @ORM\Column(name="name_full", type="string", length=512, nullable=false)
   */
  private $fullName;

  /**
   * Адрес МО
   *
   * @var string
   * @ORM\Column(name="address", type="string", length=512, nullable=false)
   */
  private $address;

  /**
   * Глав.врач
   *
   * @OneToOne(targetEntity="OrganizationChiefMedicalOfficer", mappedBy="organization", cascade={"persist", "remove"},)
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
   * @var OrganizationYear[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Organization\OrganizationYear", mappedBy="organization", cascade={"persist"}, orphanRemoval=true)
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
   * @return array
   */
  public function getYearsChoice()
  {
    $years = Year::getYears();
    $choice = [];
    foreach ($this->years->getValues() as $organization)
    {
      $choice[$years[$organization->getYear()]] = $organization->getYear();
    }

    return array_flip($choice);
  }

  /**
   * @param $choice
   */
  public function setYearsChoice($choice)
  {
    $years = array_flip(Year::getYears());
    /**
     * @var OrganizationYear $year
     */
    foreach ($this->years->getValues() as $year)
    {
      if (!array_key_exists($year->getYear(), $choice))
      {
        $this->years->removeElement($year);
      }
    }
    foreach ($choice as $item)
    {
      $find = false;
      foreach ($this->years->getValues() as $year)
      {
        if ($year->getYear() === $years[$item])
        {
          $find = true;
        }
      }
      if (!$find)
      {
        $year = new OrganizationYear();
        $year->setOrganization($this);
        $year->setYear($years[$item]);
        $year->setYearKey($item);
        $this->years->add($year);
      }
    }
  }

  /**
   * @return OrganizationYear[]|ArrayCollection
   */
  public function getYears()
  {
    return $this->years;
  }

  /**
   * @param $year
   */
  public function addYear($year)
  {
    $this->years->add($year);
  }

  /**
   * @param $years
   */
  public function setYears($years): void
  {
    foreach ($years as $year)
    {
      if (!$this->years->contains($year))
      {
        $this->years->add($year);
      }
    }
  }

  /**
   * @param $year
   */
  public function removeYear($year)
  {
    if ($this->years->contains($year))
    {
      $this->years->removeElement($year);
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
   * @param $published
   * @return $this
   */
  public function setPublished($published)
  {
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
