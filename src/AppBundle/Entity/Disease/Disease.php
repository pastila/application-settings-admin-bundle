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
   * @var CategoryDisease|null
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease\CategoryDisease", inversedBy="diseases")
   * @ORM\JoinColumn(onDelete="RESTRICT")
   */
  protected $category;

  /**
   * @var string
   * @ORM\Column(type="string", nullable=false)
   */
  protected $name;

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
   * @return CategoryDisease|null
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
   * @param CategoryDisease|null $tag
   * @return $this
   */
  public function setCategory ($tag)
  {
    $this->category = $tag;
    return $this;
  }

  /**
   * @Assert\IsTrue(message="Заболевание может быть прикреплено только к подгруппе(3-й уровень)")
   */
  public function isCheckLevelCategory()
  {
    $category = $this->getCategory();

    return $category ? $category->getTreeLevel() === CategoryDisease::LEVEL_SUBGROUP : false;
  }
}
