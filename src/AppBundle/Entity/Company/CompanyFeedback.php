<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company.
 *
 * @ORM\Table(name="s_company_feedbacks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\CompanyFeedbackRepository")
 */
class CompanyFeedback
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
   * @var null|User
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User", inversedBy="users")
   * @ORM\JoinColumn(name="user_id", nullable=false, onDelete="RESTRICT")
   */
  private $user;

  /**
   * @var null|Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @ORM\JoinColumn(name="region_id", nullable=false, onDelete="RESTRICT")
   */
  private $region;

  /**
   * @var null|Company
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\Company", inversedBy="feedbacks")
   * @ORM\JoinColumn(name="company_id", nullable=false, onDelete="RESTRICT")
   */
  private $company;

  /**
   * @var null|Valuation
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\Valuation")
   * @ORM\JoinColumn(name="valuation_id", nullable=false, onDelete="RESTRICT")
   */
  private $valuation;

  /**
   * @var null|Diagnosis
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Diagnosis\Diagnosis", inversedBy="feedbacks")
   * @ORM\JoinColumn(name="diagnosis_id", nullable=false, onDelete="RESTRICT")
   */
  private $diagnosis;

  /**
   * @var string
   *
   * @ORM\Column(name="type", type="string", length=255, nullable=false)
   */
  private $text;

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return null|User
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param Region $user
   *
   * @return $this
   */
  public function setUser($user)
  {
    $this->user = $user;

    return $this;
  }

  /**
   * @return null|Region
   */
  public function getRegion()
  {
    return $this->region;
  }

  /**
   * @param Region $region
   *
   * @return $this
   */
  public function setRegion($region)
  {
    $this->region = $region;

    return $this;
  }

  /**
   * @return null|Company
   */
  public function getCompany()
  {
    return $this->company;
  }

  /**
   * @param Company $company
   *
   * @return $this
   */
  public function setCompany($company)
  {
    $this->company = $company;

    return $this;
  }

  /**
   * @return null|Valuation
   */
  public function getValuation()
  {
    return $this->valuation;
  }

  /**
   * @param Valuation $valuation
   *
   * @return $this
   */
  public function setValuation($valuation)
  {
    $this->valuation = $valuation;

    return $this;
  }

  /**
   * @return null|Diagnosis
   */
  public function getDiagnosis()
  {
    return $this->diagnosis;
  }

  /**
   * @param Diagnosis $diagnosis
   *
   * @return $this
   */
  public function setDiagnosis($diagnosis)
  {
    $this->diagnosis = $diagnosis;

    return $this;
  }

  /**
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * @param string $text
   *
   * @return string
   */
  public function setText($text)
  {
    $this->text = $text;

    return $this;
  }

  public function __toString()
  {
    return $this->id ? $this->text : '';
  }
}
