<?php

namespace AppBundle\Entity\Organization;

use AppBundle\Entity\Geo\Region;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Медицинская организация (МО).
 *
 * @ORM\Table(name="s_medical_organizations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Organization\MedicalOrganizationRepository")
 */
class MedicalOrganization
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
   * Главный врач
   * @var OrganizationChiefMedicalOfficer
   * @OneToOne(targetEntity="AppBundle\Entity\Organization\OrganizationChiefMedicalOfficer", mappedBy="organization", cascade={"persist", "remove"},)
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
   * @return OrganizationChiefMedicalOfficer
   */
  public function getChiefMedicalOfficer()
  {
    return $this->chiefMedicalOfficer;
  }

  /**
   * @param OrganizationChiefMedicalOfficer $chiefMedicalOfficer
   * @return $this
   */
  public function setChiefMedicalOfficer($chiefMedicalOfficer): MedicalOrganization
  {
    $this->chiefMedicalOfficer = $chiefMedicalOfficer;
    return $this;
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
   * @return $this
   */
  public function addYear(OrganizationYear $year): MedicalOrganization
  {
    $this->years->add($year);
    $year->setOrganization($this);
    return $this;
  }

  /**
   * @param OrganizationYear[] $years
   * @return $this
   */
  public function setYears($years): MedicalOrganization
  {
    $this->years = $years;
    return $this;
  }

  /**
   * @param OrganizationYear $year
   * @return $this
   */
  public function removeYear($year): MedicalOrganization
  {
    if ($this->years->contains($year))
    {
      $this->years->removeElement($year);
    }

    return $this;
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
   * @return $this
   */
  public function setRegion(?Region $region): MedicalOrganization
  {
    $this->region = $region;
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
   * @return $this
   */
  public function setName($name): MedicalOrganization
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
   * @return $this
   */
  public function setFullName($fullName): MedicalOrganization
  {
    $this->fullName = $fullName;
    return $this;
  }

  /**
   * @return string
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @param $code
   * @return $this
   */
  public function setCode($code): MedicalOrganization
  {
    $this->code = $code;
    return $this;
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
   * @return $this
   */
  public function setAddress($address): MedicalOrganization
  {
    $this->address = $address;
    return $this;
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
  public function setPublished($published): MedicalOrganization
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
