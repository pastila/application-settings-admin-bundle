<?php

namespace AppBundle\Entity\Organization;


use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationYear.
 *
 * @ORM\Table(name="s_organization_years",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="unique_map_idx", columns={"year", "organization_code"})}))
 * @ORM\Entity()
 */
class OrganizationYear
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Год
   *
   * @var integer
   * @ORM\Column(type="integer", nullable=false)
   */
  private $year;

  /**
   * МО
   *
   * @var string|MedicalOrganization
   * @ORM\ManyToOne(targetEntity="MedicalOrganization", cascade={"persist"}, inversedBy="years")
   * @ORM\JoinColumn(name="organization_code", referencedColumnName="code", nullable=false)
   */
  protected $organization;

  /**
   * OrganizationYear constructor.
   */
  public function __construct()
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
   * @return MedicalOrganization|string
   */
  public function getOrganization()
  {
    return $this->organization;
  }

  /**
   * @param MedicalOrganization|string $organization
   */
  public function setOrganization($organization): void
  {
    $this->organization = $organization;
  }

  /**
   * @return int
   */
  public function getYear()
  {
    return $this->year;
  }

  /**
   * @param int $year
   */
  public function setYear($year): void
  {
    $this->year = $year;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->year ? (string)$this->year : 'Новый год';
  }
}
