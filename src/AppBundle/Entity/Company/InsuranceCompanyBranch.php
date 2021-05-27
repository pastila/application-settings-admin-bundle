<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;
use AppBundle\Validator\InsuranceCompany\InsuranceCompanyBranchPublished;
use Doctrine\ORM\PersistentCollection;

/**
 * InsuranceCompanyBranch.
 *
 * @ORM\Table(
 *   name="s_company_branches"
 * )
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
   * Уникальный номер СМО
   *
   * @var integer
   *
   * @ORM\Column(type="bigint", nullable=true)
   */
  private $code;

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
   * @ORM\ManyToOne(targetEntity="InsuranceCompany", cascade={"persist"}, inversedBy="branches")
   * @ORM\JoinColumn(name="company_id", nullable=true, onDelete="RESTRICT")
   */
  private $company;

  /**
   * Регион
   *
   * @var null|Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region", inversedBy="branches")
   * @ORM\JoinColumn(name="region_id", nullable=true, onDelete="RESTRICT")
   */
  private $region;

  /**
   * @var boolean
   * @ORM\Column(type="boolean", nullable=false, options={"default"=true})
   * @InsuranceCompanyBranchPublished
   */
  private $published;

  /**
   * Телефон горячей линии филила в регионе
   *
   * @var string
   *
   * @ORM\Column( type="string", length=255, nullable=true)
   */
  private $phones;

  /**
   * ФИО руководителя (дат. пад.)
   *
   * @var string
   *
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $bossFullNameDative;

  /**
   * @var InsuranceRepresentative[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="InsuranceRepresentative", mappedBy="branch", cascade={"persist"}, orphanRemoval=true)
   */
  private $representatives;

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
   * @param $representative
   */
  public function addRepresentative($representative)
  {
    $representative->setBranch($this);
    $this->representatives->add($representative);
  }

  /**
   * @param $representative
   */
  public function removeRepresentative($representative)
  {
    /**
     * @see PersistentCollection::removeElement()
     * Нам нужен orphanRemoval, чтобы Doctrine удаляла элементы коллекции, но в реализации PersistentCollection
     * метод removeElement отправляет элементы в очередь на удаление и они удаляются не зависимо от того был сохранен
     * родительский объект или нет, соответственно меняем коллекцтю на ArrayCollection
     */
    if ($this->representatives instanceof PersistentCollection)
    {
      $this->representatives = new ArrayCollection($this->representatives->toArray());
    }

    if ($this->representatives->contains($representative))
    {
      $this->representatives->removeElement($representative);
    }
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
   * @return boolean
   * @deprecated
   */
  public function getPublished()
  {
    return $this->isPublished();
  }

  /**
   * @return bool
   */
  public function isPublished ()
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
  public function getPhones()
  {
    return $this->phones;
  }

  /**
   * @param string $phones
   */
  public function setPhones($phones): void
  {
    $this->phones = $phones;
  }

  /**
   * @return int
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @param int $code
   */
  public function setCode($code): void
  {
    $this->code = $code;
  }

  /**
   * @return string
   */
  public function getBossFullNameDative()
  {
    return $this->bossFullNameDative;
  }

  /**
   * @param string $bossFullNameDative
   */
  public function setBossFullNameDative($bossFullNameDative): void
  {
    $this->bossFullNameDative = $bossFullNameDative;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ?  ($this->company ? $this->company->getName() : '') : 'Новое отделение';
  }
}