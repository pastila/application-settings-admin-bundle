<?php

namespace AppBundle\Entity\Organization;


use AppBundle\Entity\Company\Company;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationYear.
 *
 * @ORM\Table(name="s_organization_years")
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
   * @var string|Organization
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organization\Organization")
   * @ORM\JoinColumn(name="organization_id", referencedColumnName="code", nullable=false, onDelete="RESTRICT")
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
   * @return Organization|string
   */
  public function getOrganization()
  {
    return $this->organization;
  }

  /**
   * @param Organization|string $organization
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
