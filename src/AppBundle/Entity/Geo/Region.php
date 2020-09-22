<?php

namespace AppBundle\Entity\Geo;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="s_regions")
 * @ORM\Entity()
 */
class Region
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
  public function __toString()
  {
    return $this->getId() ? $this->getName() : 'Новый регион';
  }
}
