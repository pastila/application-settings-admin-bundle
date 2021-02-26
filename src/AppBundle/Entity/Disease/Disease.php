<?php

namespace AppBundle\Entity\Disease;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Disease.
 *
 * @ORM\Table(name="s_diseases")
 * @ORM\Entity()
 */
class Disease
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * @var DiseaseCategory|null
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease\DiseaseCategory", inversedBy="diseases")
   * @ORM\JoinColumn(onDelete="RESTRICT")
   */
  protected $category;

  /**
   * @var string
   * @ORM\Column(type="string", nullable=false)
   */
  protected $name;

  /**
   * @var string
   * @ORM\Column(type="string", length=8, nullable=false)
   */
  protected $code;

  /**
   * @return string
   */
  public function __toString ()
  {
    return $this->id ? $this->getName() : '-';
  }

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
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return DiseaseCategory|null
   */
  public function getCategory ()
  {
    return $this->category;
  }

  /**
   * @return int
   */
  public function getCategoryId ()
  {
    return $this->category ? $this->category->getId() : null;
  }

  /**
   * @param DiseaseCategory|null $tag
   * @return $this
   */
  public function setCategory ($tag)
  {
    $this->category = $tag;
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
   * @param string $code
   */
  public function setCode($code): void
  {
    $this->code = $code;
  }

  /**
   * @Assert\IsTrue(message="Заболевание может быть прикреплено только к подгруппе(3-й уровень)")
   */
  public function isCheckLevelCategory()
  {
    $category = $this->getCategory();

    return $category ? $category->getTreeLevel() === DiseaseCategory::DEPTH_SUBGROUP : false;
  }
}
