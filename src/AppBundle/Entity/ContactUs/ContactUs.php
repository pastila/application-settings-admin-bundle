<?php

namespace AppBundle\Entity\ContactUs;

use AppBundle\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="s_contact_us")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactUs\ContactUsRepository")
 */
class ContactUs
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
   * Пользователь(отправитель), который оставил отзыв, если он был авторизован
   *
   * @var null|User
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User")
   * @ORM\JoinColumn(name="user_id", nullable=true, onDelete="RESTRICT")
   */
  private $author;

  /**
   * Имя отправителя
   *
   * @var null|string
   * @ORM\Column(type="string", length=512, nullable=true)
   */
  protected $authorName;

  /**
   * Email отправителя
   *
   * @Assert\Email
   * @var null|string
   * @ORM\Column(type="string", length=256, nullable=true)
   */
  protected $email;

  /**
   * Сообщение от отправителя
   *
   * @var null|string
   * @ORM\Column(type="string", length=512, nullable=true)
   */
  protected $message;

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
  public function getAuthorName()
  {
    return $this->authorName;
  }

  /**
   * @param string $author_name
   *
   * @return string
   */
  public function setAuthorName($author_name)
  {
    $this->authorName = $author_name;

    return $this;
  }

  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param string $email
   *
   * @return string
   */
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @param string $message
   *
   * @return string
   */
  public function setMessage($message)
  {
    $this->message = $message;

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
   * @return string
   */
  public function __toString()
  {
    return $this->getId() ? $this->getAuthorName() : 'Новое сообщение';
  }
}
