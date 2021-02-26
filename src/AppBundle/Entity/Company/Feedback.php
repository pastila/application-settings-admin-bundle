<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Feedback.
 *
 * @ORM\Table(name="s_company_feedbacks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Company\FeedbackRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Feedback
{
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * ID отзыва в системе битрикс
   *
   * @var integer
   *
   * @ORM\Column(name="bitrix_id", type="integer", nullable=true)
   */
  private $bitrixId;

  /**
   * Пользователь, который оставил отзыв
   *
   * @var null|User
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User")
   * @ORM\JoinColumn(name="user_id", nullable=true, onDelete="RESTRICT")
   */
  private $author;

  /**
   * Имя пользователя, который оставил отзыв, но не стал авторизовываться
   *
   * @var string
   *
   * @ORM\Column(name="author_name", type="string", length=255, nullable=true)
   */
  private $authorName;


  /**
   * Филиал компании
   *
   * @var null|InsuranceCompanyBranch
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\InsuranceCompanyBranch")
   * @ORM\JoinColumn(name="branch_id", nullable=true, onDelete="RESTRICT")
   */
  private $branch;

  /**
   * Оценка
   *
   * @var integer
   *
   * @ORM\Column(name="valuation", type="integer", nullable=true)
   * @Assert\Range(
   *      min = 1,
   *      max = 5,
   *      minMessage = "Оценка не может быть менее {{ limit }}",
   *      maxMessage = "Оценка не может быть более {{ limit }}"
   * )
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
   * @var
   */
  private $region;

  /**
   * @var
   */
  private $company;

  /**
   * @var \DateTime
   * @ORM\Column(type="datetime")
   */
  protected $createdAt;

  /**
   * @var \DateTime
   * @ORM\Column(type="datetime", nullable=true)
   */
  protected $updatedAt;

  /**
   * Sets createdAt.
   *
   * @param \DateTime $createdAt
   * @return $this
   */
  public function setCreatedAt(\DateTime $createdAt)
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * Returns createdAt.
   *
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * Sets updatedAt.
   *
   * @param \DateTime $updatedAt
   * @return $this
   */
  public function setUpdatedAt(\DateTime $updatedAt = null)
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  /**
   * Returns updatedAt.
   *
   * @return \DateTime
   */
  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  /**
   * Company constructor.
   */
  public function __construct()
  {
    $this->comments = new ArrayCollection();
  }

  /**
   * @return Comment[]|ArrayCollection
   */
  public function getComments()
  {
    return $this->comments;
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return int
   */
  public function getBitrixId()
  {
    return $this->bitrixId;
  }

  /**
   * @param $bitrixId
   * @return $this
   */
  public function setBitrixId($bitrixId)
  {
    $this->bitrixId = $bitrixId;

    return $this;
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
    return $this->getBranch() ? $this->getBranch()->getRegion() : null;
  }

  /**
   * @return null|Region
   */
  public function getRegionRaw()
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
   * @return InsuranceCompany|null
   */
  public function getCompany()
  {
    return $this->getBranch() ? $this->getBranch()->getCompany() : null;
  }

  /**
   * @return null|Region
   */
  public function getCompanyRaw()
  {
    return $this->company;
  }

  /**
   * @param $company
   * @return $this
   */
  public function setCompany($company)
  {
    $this->company = $company;

    return $this;
  }

  /**
   * @return null|InsuranceCompanyBranch
   */
  public function getBranch()
  {
    return $this->branch;
  }

  /**
   * @param InsuranceCompanyBranch $branch
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

  /**
   * @return int|string
   */
  public function getModerationStatusLabel()
  {
    if ($this->moderationStatus === FeedbackModerationStatus::MODERATION_ACCEPTED)
    {
      return 'Одобрено';
    } elseif ($this->moderationStatus === FeedbackModerationStatus::MODERATION_REJECTED)
    {
      return 'Отклонено';
    } elseif ($this->moderationStatus === FeedbackModerationStatus::MODERATION_NONE)
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

  public function isFromLetter()
  {
    return $this->isFromLetter;
  }

  public function setAuthorName($v)
  {
    $this->authorName = $v;

    return $this;
  }

  public function getAuthorName()
  {
    return $this->authorName ?: ($this->getAuthor() ? $this->getAuthor()->getFullName() : '');
  }

  /**
   * @ORM\PrePersist()
   */
  public function prePersist()
  {
    if (!$this->createdAt)
    {
      $this->createdAt = new \DateTime();
    }
  }

  /**
   * @ORM\PreUpdate()
   * @param PreUpdateEventArgs $args
   */
  public function preUpdate(PreUpdateEventArgs $args)
  {
    if (
      !$args->hasChangedField('updatedAt') &&
      ($args->hasChangedField('title')
        || $args->hasChangedField('text')
        || $args->hasChangedField('valuation'))
    )
    {
      $this->updatedAt = new \DateTime();
    }
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->title : '';
  }
}
