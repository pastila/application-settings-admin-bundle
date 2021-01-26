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
 * @ORM\Table(name="s_company_branches", indexes={@ORM\Index(name="bitrix_id_idx", columns={"bitrix_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\CompanyBranchRepository")
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
   * @var integer|null
   *
   * @ORM\Column(type="integer", nullable=true)
   */
  private $bitrixId;

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
   * Email №1 компании
   *
   * @var string
   *
   * @ORM\Column(name="email_first", type="string", length=256, nullable=true)
   */
  private $emailFirst;


  /**
   * Email №2 компании
   *
   * @var string
   *
   * @ORM\Column(name="email_second", type="string", length=256, nullable=true)
   */
  private $emailSecond;


  /**
   * Email №2 компании
   *
   * @var string
   *
   * @ORM\Column(name="email_third", type="string", length=256, nullable=true)
   */
  private $emailThird;

  /**
   * @var boolean
   * @ORM\Column(type="boolean", nullable=false, options={"default"=true})
   */
  private $published;

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
  public function getEmailFirst()
  {
    return $this->emailFirst;
  }

  /**
   * @param string $emailFirst
   * @return $this
   */
  public function setEmailFirst($emailFirst)
  {
    $this->emailFirst = $emailFirst;

    return $this;
  }

  /**
   * @return string
   */
  public function getEmailSecond()
  {
    return $this->emailSecond;
  }

  /**
   * @param string $emailSecond
   * @return $this
   */
  public function setEmailSecond($emailSecond)
  {
    $this->emailSecond = $emailSecond;

    return $this;
  }

  /**
   * @return string
   */
  public function getEmailThird()
  {
    return $this->emailThird;
  }

  /**
   * @param string $emailThird
   * @return $this
   */
  public function setEmailThird($emailThird)
  {
    $this->emailThird = $emailThird;

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
   * @return int|string
   */
  public function getStatusLabel ()
  {
    return $this->published ? 'Опубликован' : 'Не опубликован';
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
   * @return int|null
   */
  public function getBitrixId(): ?int
  {
    return $this->bitrixId;
  }

  /**
   * @param int|null $bitrixId
   * @return CompanyBranch
   */
  public function setBitrixId(?int $bitrixId): CompanyBranch
  {
    $this->bitrixId = $bitrixId;
    return $this;
  }

  /**
   * @return string
   */
  public function getRegionName()
  {
    return $this->region . ' ' . $this->name ;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->name : 'Новое отделение';
  }
}
