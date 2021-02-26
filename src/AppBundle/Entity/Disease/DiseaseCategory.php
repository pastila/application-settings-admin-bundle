<?php

namespace AppBundle\Entity\Disease;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="s_disease_categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Disease\DiseaseCategoryRepository")
 * @Gedmo\Tree(type="nested")
 */
class DiseaseCategory
{
  /**
   * Уровень в дереве(подгруппа), на которую ссылается болезнь
   */
  const DEPTH_SUBGROUP = 3;
  
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
   * @var DiseaseCategory
   * @Gedmo\TreeRoot()
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease\DiseaseCategory")
   * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
   */
  protected $treeRoot;

  /**
   * @var DiseaseCategory
   * @Gedmo\TreeParent
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease\DiseaseCategory", inversedBy="children")
   * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
   * @Gedmo\SortableGroup()
   */
  private $parent;

  /**
   * @var DiseaseCategory[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Disease\DiseaseCategory", mappedBy="parent")
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
   * DiseaseCategory constructor.
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
    return $this->getId() ? $this->getName() : 'Новый раздел';
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
   * @return DiseaseCategory
   */
  public function getTreeRoot ()
  {
    return $this->treeRoot;
  }

  /**
   * @param DiseaseCategory $treeRoot
   * @return $this
   */
  public function setTreeRoot (DiseaseCategory $treeRoot)
  {
    $this->treeRoot = $treeRoot;
    return $this;
  }

  /**
   * @return DiseaseCategory
   */
  public function getParent ()
  {
    return $this->parent;
  }

  /**
   * @param DiseaseCategory $parent
   * @return $this
   */
  public function setParent ($parent)
  {
    $this->parent = $parent;
    return $this;
  }

  /**
   * @return DiseaseCategory[]|ArrayCollection
   */
  public function getChildren ()
  {
    return $this->children;
  }

  /**
   * @param DiseaseCategory[]|ArrayCollection $children
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
   * @param DiseaseCategory $category
   * @return $this
   */
  public function addChild (DiseaseCategory $category)
  {
    $category->setParent($this);
    $this->children->add($category);
    return $this;
  }

  /**
   * @param DiseaseCategory $category
   * @return $this
   */
  public function removeChild (DiseaseCategory $category)
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
