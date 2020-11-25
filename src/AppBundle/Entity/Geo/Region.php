<?php

namespace AppBundle\Entity\Geo;

use Accurateweb\LocationBundle\Model\ResolvedUserLocation;
use Accurateweb\LocationBundle\Model\UserLocationInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="s_regions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Geo\RegionRepository")
 */
class Region implements UserLocationInterface
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Название региона
   *
   * @var null|string
   * @ORM\Column(type="string", length=512, nullable=false, unique=true)
   */
  protected $name;

  /**
   * Название региона(транслит)
   *
   * @var null|string
   * @ORM\Column(type="string", length=512, nullable=false, unique=true)
   */
  protected $code;

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
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
   *
   * @return string
   */
  public function setCode($code)
  {
    $this->code = $code;

    return $this;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  public function getNameWithoutCode()
  {
    return trim(preg_replace('/\d/', '',  $this->name));
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

  public function getLocationId()
  {
    return $this->getId();
  }

  public function getLocationName()
  {
    return $this->getName();
  }

  public function getResolvedLocation()
  {
    $l = new ResolvedUserLocation();
    $l->setRegionName($this->getName());

    return $l;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->getId() ? $this->getName() : 'Новый регион';
  }
}
