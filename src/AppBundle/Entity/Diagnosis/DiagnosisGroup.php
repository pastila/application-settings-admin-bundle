<?php

namespace AppBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiagnosisGroup.
 *
 * @ORM\Table(name="s_diagnosis_groups")
 * @ORM\Entity()
 */
class DiagnosisGroup
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
   * @var null|DiagnosisClass
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Diagnosis\DiagnosisClass", inversedBy="groups")
   * @ORM\JoinColumn(name="class_id", nullable=false, onDelete="RESTRICT")
   */
  private $class;

  /**
   * @var ArrayCollection|Diagnosis[]
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Diagnosis\Diagnosis", mappedBy="group")
   */
  private $diagnoses;

  /**
   * DiagnosisClass constructor.
   */
  public function __construct()
  {
    $this->diagnoses = new ArrayCollection();
  }

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
   * @return DiagnosisClass|null
   */
  public function getClass()
  {
    return $this->class;
  }

  /**
   * @param DiagnosisClass $class
   *
   * @return $this
   */
  public function setClass($class)
  {
    $this->class = $class;

    return $this;
  }

  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
