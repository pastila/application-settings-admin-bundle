<?php

namespace AppBundle\Model\Filter;

use AppBundle\Entity\User\User;

class FeedbackFilter
{
  /**
   * @var User
   */
  private $author;

  /**
   * @var integer
   */
  private $moderationStatus;

  /**
   * @var integer[]
   */
  private $moderationStatuses;

  /**
   * @return User
   */
  public function getAuthor ()
  {
    return $this->author;
  }

  /**
   * @param User $author
   * @return $this
   */
  public function setAuthor ($author)
  {
    $this->author = $author;
    return $this;
  }

  /**
   * @return int
   */
  public function getModerationStatus ()
  {
    return $this->moderationStatus;
  }

  /**
   * @param int $moderationStatus
   * @return $this
   */
  public function setModerationStatus ($moderationStatus)
  {
    $this->moderationStatus = $moderationStatus;
    return $this;
  }

  /**
   * @return integer[]
   */
  public function getModerationStatuses ()
  {
    return $this->moderationStatuses;
  }

  /**
   * @param integer[] $moderationStatuses
   * @return $this
   */
  public function setModerationStatuses ($moderationStatuses)
  {
    $this->moderationStatuses = $moderationStatuses;
    return $this;
  }
}