<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use AppBundle\Model\Checkout\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Feedback.
 *
 * @ORM\Table(name="s_company_feedbacks")
 * @ORM\Entity()
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
  private $author;

  /**
   * Регион
   *
   * @var null|Region
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geo\Region")
   * @ORM\JoinColumn(name="region_id", nullable=true, onDelete="RESTRICT")
   */
  private $region;

  /**
   * Филиал компании
   *
   * @var null|CompanyBranch
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\CompanyBranch")
   * @ORM\JoinColumn(name="branch_id", nullable=true, onDelete="RESTRICT")
   */
  private $branch;

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
   * Флаг, что отзыв из письма
   *
   * @var integer
   *
   * @ORM\Column(name="review_letter", type="integer", nullable=true)
   */
  private $reviewLetter;

  /**
   * @var int
   * @ORM\Column(type="integer", nullable=false)
   */
  private $moderationStatus = FeedbackModerationStatus::MODERATION_NONE;

  /**
   * @var boolean
   */
  private $isFromLetter;

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
  public function getAuthor()
  {
    return $this->author;
  }

  /**
   * @param User $author
   *
   * @return $this
   */
  public function setAuthor($author)
  {
    $this->author = $author;

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
   * @return null|CompanyBranch
   */
  public function getBranch()
  {
    return $this->branch;
  }

  /**
   * @param CompanyBranch $branch
   *
   * @return $this
   */
  public function setBranch($branch)
  {
    $this->branch = $branch;

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
  public function getReviewLetter()
  {
    return $this->reviewLetter;
  }

  /**
   * @param string $reviewLetter
   *
   * @return string
   */
  public function setReviewLetter($reviewLetter)
  {
    $this->reviewLetter = $reviewLetter;

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

  public function getModerationStatusLabel ()
  {
    if ($this->moderationStatus === FeedbackModerationStatus::MODERATION_ACCEPTED)
    {
      return 'Одобрено';
    }
    elseif ($this->moderationStatus === FeedbackModerationStatus::MODERATION_REJECTED)
    {
      return 'Отклонено';
    }
    elseif ($this->moderationStatus === FeedbackModerationStatus::MODERATION_NONE)
    {
      return 'Неизвестно';
    }

    return $this->getModerationStatus();
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
   * @return Company|null
   */
  public function getCompany()
  {
    return $this->getBranch() ? $this->getBranch()->getCompany() : null;
  }

  public function isFromLetter()
  {
    return $this->isFromLetter;
  }

  public function setAuthorName($v)
  {
    $author = $this->getAuthor();
    if (!$author)
    {
      $author = new User();
      $this->setAuthor($author);
    }

    $author->setFirstName($v);

    return $this;
  }

  public function getAuthorName()
  {
    $author = $this->getAuthor();

    return $author ? $author->getFirstName() : null;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->text : '';
  }
}
