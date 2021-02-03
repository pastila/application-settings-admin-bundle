<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;use Doctrine\ORM\Mapping\OneToOne;

/**
 * InsuranceCompanyBranch.
 *
 * @ORM\Table(name="s_company_branches")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\InsuranceCompanyBranchRepository")
 */
class InsuranceCompanyBranch
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Кпп компании
   *
   * @var string
   *
   * @ORM\Column(name="kpp", type="string", length=256, nullable=true)
   */
  private $kpp;

  /**
   * Оценка
   *
   * @var float
   * @ORM\Column(name="valuation", type="decimal", precision=8, scale=3, nullable=true)
   */
  private $valuation;

  /**
   * Головная компания
   *
   * @var null|string|InsuranceCompany
   * @ORM\ManyToOne(targetEntity="InsuranceCompany", cascade={"persist"})
   * @ORM\JoinColumn(name="company_id", nullable=true, onDelete="RESTRICT")
   */
  private $company;

  /**
   * Регион
   *
   * @var null|Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @ORM\JoinColumn(name="region_id", nullable=true, onDelete="RESTRICT")
   */
  private $region;

  /**
   * @var boolean
   * @ORM\Column(type="boolean", nullable=false, options={"default"=true})
   */
  private $published;

  /**
   * @var InsuranceRepresentative[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="InsuranceRepresentative", mappedBy="branch", cascade={"persist"})
   */
  private $representatives; // TODO: orphanremoval=true

  /**
   * @var Feedback[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\Feedback", mappedBy="branch")
   */
  protected $feedbacks;

  /**
   * Company constructor.
   */
  public function __construct ()
  {
    $this->feedbacks = new ArrayCollection();
    $this->representatives = new ArrayCollection();
  }

  /**
   * @return Feedback[]|ArrayCollection
   */
  public function getFeedbacks()
  {
    return $this->feedbacks;
  }

  /**
   * @return InsuranceRepresentative[]|ArrayCollection
   */
  public function getRepresentatives()
  {
    return $this->representatives;
  }

  /**
   * @param $representatives
   */
  public function setRepresentatives($representatives): void
  {
    $this->representatives = $representatives;
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
  public function getKpp()
  {
    return $this->kpp;
  }

  /**
   * @param string $kpp
   */
  public function setKpp($kpp)
  {
    $this->kpp = $kpp;
  }

  /**
   * @return float
   */
  public function getValuation()
  {
    return $this->valuation;
  }

  /**
   * @param $valuation
   * @return $this
   */
  public function setValuation($valuation)
  {
    $this->valuation = $valuation;

    return $this;
  }

  /**
   * @return null|InsuranceCompany
   */
  public function getCompany()
  {
    return $this->company;
  }

  /**
   * @param InsuranceCompany company
   *
   * @return $this
   */
  public function setCompany($company)
  {
    $this->company = $company;

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
   * @return int
   */
  public function getPublished()
  {
    return $this->published;
  }

  /**
   * @param $published
   * @return $this
   */
  public function setPublished($published)
  {
    $this->published = $published;

    return $this;
  }

  /**
   * @return string
   */
  public function getRegionName()
  {
    return $this->region . ' ' . ($this->company ? $this->company->getName() : '');
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ?  ($this->company ? $this->company->getName() : '') : 'Новое отделение';
  }
}
