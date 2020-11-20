<?php

namespace AppBundle\Entity\Menu;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/** @MappedSuperclass */
class AbstractMenu
{
  use TimestampableEntity;
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @Gedmo\SortablePosition
   * @var integer
   * @ORM\Column(type="integer", nullable=false)
   */
  private $position;

  /**
   * Текст в меню
   * @var string
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $text;

  /**
   * Ссылка/путь в меню
   * @Assert\Url
   * @var string
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $url;

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
  public function getPosition()
  {
    return $this->position;
  }

  /**
   * @param int $position
   * @return $this
   */
  public function setPosition($position)
  {
    $this->position = $position;
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
   * @return $this
   */
  public function setText($text)
  {
    $this->text = $text;
    return $this;
  }

  /**
   * @return string
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * @param string $url
   * @return $this
   */
  public function setUrl($url)
  {
    $this->url = $url;
    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->url;
  }
}