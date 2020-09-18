<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company.
 *
 * @ORM\Table(name="s_companies")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\CompanyRepository")
 */
class Company
{
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=512, nullable=false)
   */
  private $name;

  /**
   * @var null|Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region", inversedBy="companies")
   * @ORM\JoinColumn(name="region_id", nullable=false, onDelete="RESTRICT")
   */
  private $region;

  /**
   * @var string
   *
   * @ORM\Column(name="type", type="string", length=512, nullable=false)
   */
  private $legalAddress;

  /**
   * @var string
   *
   * @ORM\Column(name="experience", type="string", length=255, nullable=false)
   */
  private $director;

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
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
   * @return null|Region
   */
  public function getRegion()
  {
    return $this->region;
  }

  /**
   * @param Region $region
   *
   * @return $this
   */
  public function setRegion($region)
  {
    $this->region = $region;

    return $this;
  }

  /**
   * @return string
   */
  public function getLegalAddress()
  {
    return $this->legalAddress;
  }

  /**
   * @param string $legalAddress
   *
   * @return string
   */
  public function setLegalAddress($legalAddress)
  {
    $this->legalAddress = $legalAddress;

    return $this;
  }

  /**
   * @return string
   */
  public function getDirector()
  {
    return $this->director;
  }

  /**
   * @param string $director
   *
   * @return string
   */
  public function setDirector($director)
  {
    $this->director = $director;

    return $this;
  }

  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
