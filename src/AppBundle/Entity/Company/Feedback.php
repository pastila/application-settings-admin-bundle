<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Feedback.
 *
 * @ORM\Table(name="s_company_feedbacks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\CompanyFeedbackRepository")
 */
class Feedback
{
  use TimestampableEntity;

  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * Пользователь, который оставил отзыв
   *
   * @var null|User
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User")
   * @ORM\JoinColumn(name="user_id", nullable=false, onDelete="RESTRICT")
   */
  private $user;

  /**
   * Регион
   *
   * @var null|Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @ORM\JoinColumn(name="region_id", nullable=false, onDelete="RESTRICT")
   */
  private $region;

  /**
   * Компания
   *
   * @var null|Company
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\Company", inversedBy="feedbacks")
   * @ORM\JoinColumn(name="company_id", nullable=false, onDelete="RESTRICT")
   */
  private $company;

  /**
   * Оценка
   *
   * @var integer
   *
   * @ORM\Column(name="valuation", type="integer", nullable=false)
   */
  private $valuation;

  /**
   * Заголовок отзыва
   *
   * @var string
   *
   * @ORM\Column(name="head", type="string", length=255, nullable=false)
   */
  private $head;

  /**
   * Описание отзыва
   *
   * @var string
   *
   * @ORM\Column(name="text", type="text", nullable=false)
   */
  private $text;

  /**
   * @return integer
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
   * @return string
   */
  public function getHead()
  {
    return $this->head;
  }

  /**
   * @param string $head
   *
   * @return string
   */
  public function setHead($head)
  {
    $this->head = $head;

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
