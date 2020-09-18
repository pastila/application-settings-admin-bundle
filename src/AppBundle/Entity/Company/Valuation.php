<?php

namespace AppBundle\Entity\Company;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Valuation.
 *
 * @ORM\Table(name="s_valuations")
 * @ORM\Entity()
 */
class Valuation
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
   * @ORM\Column(name="name", type="string", length=256, nullable=false)
   */
  private $name;

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

  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
