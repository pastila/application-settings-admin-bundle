<?php

namespace AppBundle\Entity\Menu;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractMenu
 * @package AppBundle\Entity\Menu
 */
abstract class AbstractMenu
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
   * @Gedmo\SortablePosition
   * @var integer
   * @ORM\Column(type="integer", nullable=false, options={"default"=0})
   */
  protected $position = 0;

  /**
   * Текст в меню
   * @var string
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  protected $text;

  /**
   * Ссылка/путь в меню
   * @Assert\Url
   * @var string
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  protected $url;

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