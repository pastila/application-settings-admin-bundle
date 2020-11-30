<?php


namespace AppBundle\Entity\Obrashcheniya;

use AppBundle\Entity\User\User;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="s_obrashcheniya_emails")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Obrashcheniya\ObrashcheniyaEmailRepository")
 */
class ObrashcheniyaEmail
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
   * Пользователь, которому принадлежит файл
   *
   * @var null|User
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User")
   * @ORM\JoinColumn(name="user_id", nullable=true, onDelete="RESTRICT")
   */
  private $author;

  /**
   * Данные для отправки
   *
   * @var string
   *
   * @ORM\Column(name="data", type="text", nullable=false)
   */
  private $data;

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
   * @return string
   */
  public function getData()
  {
    return $this->data;
  }

  /**
   * @param $data
   * @return $this
   */
  public function setData($data)
  {
    $this->data = $data;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->id ? $this->author : '';
  }
}