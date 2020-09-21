<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
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
   * @ORM\JoinColumn(name="user_id", nullable=true, onDelete="RESTRICT")
   */
  private $user;

  /**
   * Регион
   *
   * @var null|Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @ORM\JoinColumn(name="region_id", nullable=true, onDelete="RESTRICT")
   */
  private $region;

  /**
   * Компания
   *
   * @var null|Company
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\Company", inversedBy="feedbacks")
   * @ORM\JoinColumn(name="company_id", nullable=true, onDelete="RESTRICT")
   */
  private $company;

  /**
   * Филиал компании
   *
   * @var null|CompanyBranch
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\CompanyBranch", inversedBy="feedbacks")
   * @ORM\JoinColumn(name="company_branch_id", nullable=true, onDelete="RESTRICT")
   */
  private $companyBranch;

  /**
   * Оценка
   *
   * @var integer
   *
   * @ORM\Column(name="valuation", type="integer", nullable=true)
   */
  private $valuation;

  /**
   * Заголовок отзыва
   *
   * @var string
   *
   * @ORM\Column(name="title", type="string", length=255, nullable=true)
   */
  private $title;

  /**
   * Описание отзыва
   *
   * @var string
   *
   * @ORM\Column(name="text", type="text", nullable=false)
   */
  private $text;

  /**
   * @var int
   * @ORM\Column(type="integer", nullable=false)
   */
  private $moderationStatus = FeedbackModerationStatus::MODERATION_NONE;

  /**
   * @var Comment[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\Comment", mappedBy="feedback")
   */
  protected $comments;

  /**
   * Company constructor.
   */
  public function __construct ()
  {
    $this->comments = new ArrayCollection();
  }

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
   * @return null
   */
  public function getValuation()
  {
    return $this->valuation;
  }

  /**
   * @param $valuation
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
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param string $title
   *
   * @return string
   */
  public function setTitle($title)
  {
    $this->title = $title;

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

  /**
   * @return int
   */
  public function getModerationStatus()
  {
    return $this->moderationStatus;
  }

  /**
   * @param int $moderationStatus
   * @return Feedback
   */
  public function setModerationStatus($moderationStatus)
  {
    if (null !== $moderationStatus && !in_array($moderationStatus, FeedbackModerationStatus::getAvailableValues()))
    {
      throw new \InvalidArgumentException();
    }

    $this->moderationStatus = $moderationStatus;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->text : '';
  }
}
