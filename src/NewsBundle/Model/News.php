<?php
/**
 * Created by PhpStorm.
 * User: eobuh
 * Date: 30.05.2018
 * Time: 11:30
 */

namespace NewsBundle\Model;

use Accurateweb\MediaBundle\Annotation\Image;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Media\Text\NewsImage;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class News
 * @ORM\Entity(repositoryClass="NewsBundle\Repository\NewsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class News implements ImageAwareInterface
{
  /**
   * @var integer
   * @ORM\Column(type="integer")
   * @ORM\Id()
   * @ORM\GeneratedValue()
   */
  protected $id;

  /**
   * @var string
   * @ORM\Column(type="string", length=256)
   * @Assert\NotBlank(message="Поле заголовок не может быть пустым")
   */
  protected $title;

  /**
   * @var string
   * @ORM\Column(type="text")
   * @Assert\NotBlank(message="Поле не может быть пустым")
   */
  protected $announce;

  /**
   * @var string
   * @ORM\Column(type="text", nullable=true)
   * @ Assert\NotBlank(message="Поле текст не может быть пустым")
   */
  protected $text;

  /**
   * @var bool
   * @ORM\Column(type="boolean")
   */
  protected $isPublished = false;

  /**
   * @var \DateTime
   * @ORM\Column(type="datetime", nullable=true)
   */
  protected $publishedAt;

  /**
   * @var \DateTime
   * @ORM\Column(type="datetime")
   */
  protected $createdAt;

  /**
   * @var string
   * @ORM\Column(name="image", length=255, nullable=true)
   * @Image(id="teaser")
   */
  protected $teaser;

  /**
   * @var array
   *
   * @ORM\Column(type="json_array", nullable=true)
   */
  protected $teaserImageOptions;

  /**
   * @var boolean
   * @ORM\Column(type="boolean", options={"default": 0})
   */
  protected $isExternal = true;
  /**
   * @var string
   * @ORM\Column(type="string", length=256, nullable=true)
   */
  protected $externalUrl;

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId($id)
  {
    $this->id = $id;
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
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }

  /**
   * @return string
   */
  public function getAnnounce()
  {
    return $this->announce;
  }

  /**
   * @param string $announce
   */
  public function setAnnounce($announce)
  {
    $this->announce = $announce;
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
   */
  public function setText($text)
  {
    $this->text = $text;
  }

  /**
   * @return bool
   */
  public function isPublished()
  {
    return $this->isPublished;
  }

  /**
   * @param bool $isPublished
   */
  public function setIsPublished($isPublished)
  {
    $this->isPublished = $isPublished;
  }

  /**
   * @return \DateTime
   */
  public function getPublishedAt()
  {
    return $this->publishedAt;
  }

  /**
   * @param \DateTime $publishedAt
   */
  public function setPublishedAt($publishedAt)
  {
    $this->publishedAt = $publishedAt;
  }

  /**
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * @param \DateTime $createdAt
   */
  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;
  }

  /**
   * @param $id
   * @return mixed
   */
  public function getImageOptions($id)
  {
    return $this->getTeaserImageOptions();
  }

  public function setImageOptions($id)
  {
    $this->setTeaserImageOptions($id);
  }

  /**
   * @return string
   */
  public function getTeaser()
  {
    return $this->teaser;
  }

  /**
   * @param string $teaser
   * @return $this
   */
  public function setTeaser($teaser)
  {
    /*
     * Не даем сбрасывать изображение из-за пустого значения в форме
     */
    if (null !== $teaser)
    {
      $this->teaser = $teaser;
    }

    return $this;
  }

  /**
   * @return array
   */
  public function getTeaserImageOptions()
  {
    return $this->teaserImageOptions;
  }

  /**
   * @param array $teaserImageOptions
   * @return News
   */
  public function setTeaserImageOptions($teaserImageOptions)
  {
    $this->teaserImageOptions = $teaserImageOptions;

    return $this;
  }

  /**
   * @return NewsImage
   */
  public function getImage($id = null)
  {
    if (null == $this->teaser)
    {
      $matches = array();
      /*      if (false === preg_match('/<img\s+src=["\']([^"\']+)["\']/', $this->announce, $matches))
            {
              return null;
            }*/

      if (isset($matches[1]))
      {
        $this->teaser = $matches[1];
      }
      else
      {
        return null;
      }
    }

    return new NewsImage('teaser', $this->teaser, $this->getTeaserImageOptions());
  }

  /**
   * @param NewsImage $teaser
   */
  public function setImage(ImageInterface $teaser)
  {
    $this->teaser = $teaser ? $teaser->getResourceId() : null;
  }


  public function __toString()
  {
    return $this->getTitle() ? $this->getTitle() : '';
  }

  /**
   * @ORM\PrePersist()
   * @ORM\PreFlush()
   */
  public function setCreatedAtPrePersist()
  {
    if (!$this->getCreatedAt())
    {
      $this->setCreatedAt(new \DateTime());
    }
  }


  /**
   * @return string
   */
  public function getExternalUrl()
  {
    return $this->externalUrl;
  }

  /**
   * @param string $externalUrl
   * @return $this
   */
  public function setExternalUrl($externalUrl)
  {
    $this->externalUrl = $externalUrl;
    return $this;
  }

  /**
   * @return bool
   */
  public function isExternal()
  {
    return $this->isExternal;
  }

  /**
   * @param bool $isExternal
   * @return $this
   */
  public function setIsExternal($isExternal)
  {
    $this->isExternal = $isExternal;
    return $this;
  }

  public function setTeaserImage (ImageInterface $teaser)
  {
    $this->setImage($teaser);
  }

  public function getTeaserImage ()
  {
    return $this->getImage();
  }
}