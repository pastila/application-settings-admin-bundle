<?php

namespace AppBundle\Entity\Disease;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="s_disease_categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Disease\CategoryDiseaseRepository")
 * @Gedmo\Tree(type="nested")
 */
class CategoryDisease
{
  /**
   * Уровень в дереве(подгруппа), на которую ссылается болезнь
   */
  const LEVEL_SUBGROUP = 3;
  
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * @var string
   * @ORM\Column(type="string", nullable=false)
   */
  protected $name;

  /**
   * @var Resource[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Disease\Disease", mappedBy="category")
   */
  protected $diseases;

  /**
   * @var int
   * @Gedmo\TreeLeft
   * @ORM\Column(type="integer")
   */
  protected $treeLeft;

  /**
   * @var int
   * @Gedmo\TreeLevel
   * @ORM\Column(type="integer")
   */
  protected $treeLevel = 0;

  /**
   * @var int
   * @Gedmo\TreeRight
   * @ORM\Column(type="integer")
   */
  protected $treeRight;

  /**
   * @var CategoryDisease
   * @Gedmo\TreeRoot()
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease\CategoryDisease")
   * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
   */
  protected $treeRoot;

  /**
   * @var CategoryDisease
   * @Gedmo\TreeParent
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease\CategoryDisease", inversedBy="children")
   * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
   * @Gedmo\SortableGroup()
   */
  private $parent;

  /**
   * @var CategoryDisease[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Disease\CategoryDisease", mappedBy="parent")
   * @ORM\OrderBy({"position" = "ASC"})
   */
  private $children;

  /**
   * @var int
   * @ORM\Column(type="integer")
   * @Gedmo\SortablePosition()
   */
  private $position;

  /**
   * CategoryDisease constructor.
   */
  public function __construct ()
  {
    $this->diseases = new ArrayCollection();
    $this->children = new ArrayCollection();
  }

  /**
   * @return string
   */
  public function __toString ()
  {
    return $this->getId() ? $this->getName() : '-';
  }

  /**
   * @return int
   */
  public function getId ()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName ()
  {
    return $this->name;
  }

  /**
   * @param string $name
   * @return $this
   */
  public function setName ($name)
  {
    $this->name = $name;
    return $this;
  }

  /**
   * @return Disease[]|ArrayCollection
   */
  public function getDiseases ()
  {
    return $this->diseases;
  }

  /**
   * @return int
   */
  public function getTreeLeft ()
  {
    return $this->treeLeft;
  }

  /**
   * @param int $treeLeft
   * @return $this
   */
  public function setTreeLeft ($treeLeft)
  {
    $this->treeLeft = $treeLeft;
    return $this;
  }

  /**
   * @return int
   */
  public function getTreeLevel ()
  {
    return $this->treeLevel;
  }

  /**
   * @param int $treeLevel
   * @return $this
   */
  public function setTreeLevel ($treeLevel)
  {
    $this->treeLevel = $treeLevel;
    return $this;
  }

  /**
   * @return int
   */
  public function getTreeRight ()
  {
    return $this->treeRight;
  }

  /**
   * @param int $treeRight
   * @return $this
   */
  public function setTreeRight ($treeRight)
  {
    $this->treeRight = $treeRight;
    return $this;
  }

  /**
   * @return CategoryDisease
   */
  public function getTreeRoot ()
  {
    return $this->treeRoot;
  }

  /**
   * @param CategoryDisease $treeRoot
   * @return $this
   */
  public function setTreeRoot (CategoryDisease $treeRoot)
  {
    $this->treeRoot = $treeRoot;
    return $this;
  }

  /**
   * @return CategoryDisease
   */
  public function getParent ()
  {
    return $this->parent;
  }

  /**
   * @param CategoryDisease $parent
   * @return $this
   */
  public function setParent ($parent)
  {
    $this->parent = $parent;
    return $this;
  }

  /**
   * @return CategoryDisease[]|ArrayCollection
   */
  public function getChildren ()
  {
    return $this->children;
  }

  /**
   * @param CategoryDisease[]|ArrayCollection $children
   * @return $this
   */
  public function setChildren ($children)
  {
    $this->children = new ArrayCollection();

    foreach ($children as $child)
    {
      $this->addChild($child);
    }

    return $this;
  }

  /**
   * @param CategoryDisease $category
   * @return $this
   */
  public function addChild (CategoryDisease $category)
  {
    $category->setParent($this);
    $this->children->add($category);
    return $this;
  }

  /**
   * @param CategoryDisease $category
   * @return $this
   */
  public function removeChild (CategoryDisease $category)
  {
    $this->children->removeElement($category);
    return $this;
  }

  /**
   * @return int
   */
  public function getPosition ()
  {
    return $this->position;
  }

  /**
   * @param int $position
   * @return $this
   */
  public function setPosition ($position)
  {
    $this->position = $position;
    return $this;
  }
}
