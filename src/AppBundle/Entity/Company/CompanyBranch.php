<?php

namespace AppBundle\Entity\Company;

use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use AppBundle\Entity\Geo\Region;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company.
 *
 * @ORM\Table(name="s_company_branches")
 * @ORM\Entity()
 */
class CompanyBranch
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Название компании
   *
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=512, nullable=false)
   */
  private $name;

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
   * @var null|string|Company
   * @ORM\ManyToOne(targetEntity="Company")
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

//  /**
//   * Фактический адресс
//   *
//   * @var string
//   *
//   * @ORM\Column(name="type", type="string", length=512, nullable=true)
//   */
//  private $type;

  /**
   * Код компании(название на английском)
   *
   * @var string
   *
   * @ORM\Column(name="code", type="string", length=256, nullable=true)
   */
  private $code;

  /**
   * ID логотипа в системе БИТРИКС
   *
   * @var string
   *
   * @ORM\Column(name="logo_id_from_bitrix", type="string", length=256, nullable=true)
   */
  private $logoIdFromBitrix;

  /**
   * URL логотипа в системе БИТРИКС
   *
   * @var string
   *
   * @ORM\Column(name="logo_url_from_bitrix", type="string", length=256, nullable=true)
   */
  private $logoUrlFromBitrix;

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
  }

  /**
   * @return Feedback[]|ArrayCollection
   */
  public function getFeedbacks()
  {
    return $this->feedbacks;
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
   *
   * @return string
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
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
   *
   * @return string
   */
  public function setKpp($kpp)
  {
    $this->kpp = $kpp;

    return $this;
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
   * @return null|Company
   */
  public function getCompany()
  {
    return $this->company;
  }

  /**
   * @param Company company
   *
   * @return $this
   */
  public function setCompany($company)
  {
    $this->company = $company;

    return $this;
  }

  /**
   * @param string companyId
   *
   * @return $this
   */
  public function setCompanyId($companyId)
  {
    $this->company = $companyId;

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
   * @return string
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @param string $code
   *
   * @return string
   */
  public function setCode($code)
  {
    $this->code = $code;

    return $this;
  }

  /**
   * @return string
   */
  public function getLogoIdFromBitrix()
  {
    return $this->logoIdFromBitrix;
  }

  /**
   * @param string $logoIdFromBitrix
   *
   * @return string
   */
  public function setLogoIdFromBitrix($logoIdFromBitrix)
  {
    $this->logoIdFromBitrix = $logoIdFromBitrix;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->name : 'Новое отделение';
  }
}
