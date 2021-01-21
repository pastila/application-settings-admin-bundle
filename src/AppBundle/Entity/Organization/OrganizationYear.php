<?php

namespace AppBundle\Entity\Organization;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrganizationYear.
 *
 * @ORM\Table(name="s_organisation_years")
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
   * МО
   * @var Organization[]|ArrayCollection
   * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Organization\Organization", mappedBy="years")
   */
  protected $organization;

  /**
   * Год
   * @var integer
   * @ORM\Column(name="year", type="integer", nullable=false)
   */
  private $year;

  /**
   * OrganizationYear constructor.
   */
  public function __construct()
  {
    $this->organization = new ArrayCollection();
  }

  /**
   * @return Organization[]|ArrayCollection
   */
  public function getOrganization()
  {
    return $this->organization;
  }

  /**
   * @param Organization[]|ArrayCollection $organization
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
    return $this->id ? (string)$this->year : 'новый год';
  }
}
