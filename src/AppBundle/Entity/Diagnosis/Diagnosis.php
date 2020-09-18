<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Legacy\VacanciesVacancy;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Diagnosis.
 *
 * @ORM\Table(name="s_diagnoses")
 * @ORM\Entity()
 */
class Diagnosis
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
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Diagnosis\DiagnosisClass")
   * @ORM\JoinColumn(name="class_id", nullable=false, onDelete="RESTRICT")
   */
  private $class;

  /**
   * @var null|DiagnosisGroup
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Diagnosis\DiagnosisGroup")
   * @ORM\JoinColumn(name="group_id", nullable=false, onDelete="RESTRICT")
   */
  private $group;

  /**
   * @var null|DiagnosisSubGroup
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Diagnosis\DiagnosisSubGroup", inversedBy="diagnoses")
   * @ORM\JoinColumn(name="subgroup_id", nullable=false, onDelete="RESTRICT")
   */
  private $subgroup;

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

  /**
   * @return DiagnosisGroup|null
   */
  public function getGroup()
  {
    return $this->group;
  }

  /**
   * @param DiagnosisGroup $group
   *
   * @return $this
   */
  public function setGroup($group)
  {
    $this->group = $group;

    return $this;
  }

  /**
   * @return null|DiagnosisSubGroup
   */
  public function getSubGroup()
  {
    return $this->subgroup;
  }

  /**
   * @param DiagnosisSubGroup $subgroup
   *
   * @return $this
   */
  public function setSubGroup($subgroup)
  {
    $this->subgroup = $subgroup;

    return $this;
  }

  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
