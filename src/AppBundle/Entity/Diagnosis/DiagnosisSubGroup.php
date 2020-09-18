<?php

namespace AppBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiagnosisSubGroup.
 *
 * @ORM\Table(name="s_diagnosis_subgroups")
 * @ORM\Entity()
 */
class DiagnosisSubGroup
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
   * @var null|DiagnosisGroup
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Diagnosis\DiagnosisGroup", inversedBy="subgroups")
   * @ORM\JoinColumn(name="group_id", nullable=false, onDelete="RESTRICT")
   */
  private $group;

  /**
   * @var ArrayCollection|Diagnosis[]
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Diagnosis\Diagnosis", mappedBy="subgroup")
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

  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
