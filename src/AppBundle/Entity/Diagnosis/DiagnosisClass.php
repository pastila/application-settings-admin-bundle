<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Legacy\VacanciesVacancy;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DiagnosisClass.
 *
 * @ORM\Table(name="s_diagnosis_class")
 * @ORM\Entity()
 */
class DiagnosisClass
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
   * @var ArrayCollection|Diagnosis[]
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Diagnosis\Diagnosis", mappedBy="classes")
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

  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
