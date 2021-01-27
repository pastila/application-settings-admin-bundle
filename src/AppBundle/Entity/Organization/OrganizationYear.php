<?php

namespace AppBundle\Entity\Organization;

use Doctrine\Common\Collections\ArrayCollection;
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
   * Год
   *
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $year;

  /**
   * МО
   *
   * @var Organization[]|ArrayCollection
   * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Organization\Organization", mappedBy="years")
   */
  protected $organizations;

  /**
   * OrganizationYear constructor.
   */
  public function __construct()
  {
    $this->organizations = new ArrayCollection();
  }

  /**
   * @return Organization[]|ArrayCollection
   */
  public function getOrganization()
  {
    return $this->organizations;
  }

  /**
   * @param Organization[]|ArrayCollection $organization
   */
  public function setOrganization($organization): void
  {
    $this->organizations = $organization;
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
    return $this->year ? (string)$this->year : 'новый год';
  }
}
