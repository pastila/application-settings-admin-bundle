<?php

namespace AppBundle\Entity\Geo;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="s_regions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Geo\RegionRepository")
 */
class Region
{
  /**
   * @var null|int
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   * @JMS\Exclude()
   */
  protected $id;

  /**
   * Код региона.
   *
   * @var null|int
   * @ORM\Column(type="integer", nullable=false, unique=true)
   * @JMS\Type("integer")
   */
  protected $code;

  /**
   * Название региона
   *
   * @var null|string
   * @ORM\Column(type="string", nullable=false)
   * @JMS\Type("string")
   * @JMS\SerializedName("name")
   */
  protected $name;

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

  public function __toString()
  {
    return $this->getId() ? $this->getTitle() : 'Новая страна';
  }
}
