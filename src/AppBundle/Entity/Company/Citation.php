<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Citation.
 *
 * @ORM\Table(name="s_company_feedback_comment_citations")
 * @ORM\Entity()
 */
class Citation
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
   * Текст цитаты
   *
   * @var string
   *
   * @ORM\Column(name="text", type="text", nullable=false)
   */
  private $text;

  /**
   * Комментарий
   *
   * @var null|Comment
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company\Comment", inversedBy="citations")
   * @ORM\JoinColumn(name="comment_id", nullable=true, onDelete="CASCADE")
   */
  private $comment;

  /**
   * @var int
   * @ORM\Column(type="integer", nullable=true)
   */
  private $representative;

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
   * @return Comment|null
   */
  public function getComment()
  {
    return $this->comment;
  }

  /**
   * @param $comment
   * @return $this
   */
  public function setComment($comment)
  {
    $this->comment = $comment;

    return $this;
  }

  /**
   * @return int
   */
  public function getRepresentative()
  {
    return $this->representative;
  }

  /**
   * @param $representative
   * @return $this
   */
  public function setRepresentative($representative)
  {
    $this->representative = $representative;

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
