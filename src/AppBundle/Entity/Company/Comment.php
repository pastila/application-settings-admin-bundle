<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Comment.
 *
 * @ORM\Table(name="s_company_feedback_comments")
 * @ORM\Entity()
 */
class Comment
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
   * Пользователь, который оставил комментарий
   *
   * @var null|User
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User")
   * @ORM\JoinColumn(name="user_id", nullable=true, onDelete="RESTRICT")
   */
  private $user;

  /**
   * Текст комментария
   *
   * @var string
   *
   * @ORM\Column(name="text", type="text", nullable=false)
   */
  private $text;

  /**
   * Отзыв
   *
   * @var null|Feedback
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\Feedback", inversedBy="comments")
   * @ORM\JoinColumn(name="feedback_id", nullable=true, onDelete="RESTRICT")
   */
  private $feedback;

  /**
   * @var int
   * @ORM\Column(type="integer", nullable=false)
   */
  private $moderationStatus = FeedbackModerationStatus::MODERATION_NONE;

  /**
   * @var Citation[]|ArrayCollection
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\Citation", mappedBy="comment")
   */
  protected $citations;

  /**
   * Comment constructor.
   */
  public function __construct ()
  {
    $this->citations = new ArrayCollection();
  }

  /**
   * @return Citation[]|ArrayCollection
   */
  public function getCitations()
  {
    return $this->citations;
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
   * @param User $user
   *
   * @return $this
   */
  public function setUser($user)
  {
    $this->user = $user;

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
   * @return null|Feedback
   */
  public function getFeedback()
  {
    return $this->feedback;
  }

  /**
   * @param Feedback $feedback
   *
   * @return $this
   */
  public function setFeedback($feedback)
  {
    $this->feedback = $feedback;

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
   * @param $moderationStatus
   * @return $this
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
