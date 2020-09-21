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
   * Головная компания
   *
   * @var null|Company
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\Company")
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
   * Фактический адресс
   *
   * @var string
   *
   * @ORM\Column(name="type", type="string", length=512, nullable=true)
   */
  private $legalAddress;

  /**
   * Код компании(slug)
   *
   * @var string
   *
   * @ORM\Column(name="code", type="string", length=256, nullable=true)
   */
  private $code;

//  /**
//   * @var Feedback[]|ArrayCollection
//   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\Feedback", mappedBy="companyBranch")
//   */
//  protected $feedbacks;

  /**
   * Company constructor.
   */
  public function __construct ()
  {
    $this->feedbacks = new ArrayCollection();
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
  public function getLegalAddress()
  {
    return $this->legalAddress;
  }

  /**
   * @param string $legalAddress
   *
   * @return string
   */
  public function setLegalAddress($legalAddress)
  {
    $this->legalAddress = $legalAddress;

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
  public function __toString()
  {
    return $this->id ? $this->name : '';
  }
}
