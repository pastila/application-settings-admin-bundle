<?php

namespace Accurateweb\NewsBundle\Model;

use Accurateweb\MediaBundle\Annotation\Image;
use Accurateweb\NewsBundle\Media\NewsImage;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use AppBundle\Sluggable\SluggableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass()
 * @ORM\Entity(repositoryClass="Accurateweb\NewsBundle\Repository\NewsRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"slug"})
 */
class News implements ImageAwareInterface, NewsInterface, SluggableInterface
{
  use TimestampableEntity;

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
   * For use related news copy this filed in child class and set annotation:
   * ORM\ManyToMany(targetEntity="path\to\childClass")
   *
   * @var NewsInterface[]|ArrayCollection
   */
  protected $relatedNews;

  /**
   * @var string
   * @ORM\Column(type="string", length=256)
   */
  protected $slug;

  public function __construct()
  {
    $this->relatedNews = new ArrayCollection();
  }

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
    if ($this->publishedAt !== null)
    {
      $tz = new \DateTimeZone('UTC');
      return new \DateTime($this->publishedAt->format('Y-m-d H:i:s'), $tz);
    }

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
    return new NewsImage('teaser', $this->teaser, $this->getTeaserImageOptions());
  }

  /**
   * @param NewsImage $teaser
   */
  public function setImage(ImageInterface $teaser)
  {
    $this->teaser = $teaser ? $teaser->getResourceId() : null;
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

  /**
   * @return ArrayCollection|News[]
   */
  public function getRelatedNews()
  {
    return $this->relatedNews;
  }

  /**
   * @param ArrayCollection|News[] $relatedNews
   * @return $this
   */
  public function setRelatedNews($relatedNews)
  {
    $this->relatedNews = $relatedNews;
    return $this;
  }

  /**
   * @return string
   */
  public function getSlug()
  {
    return $this->slug;
  }

  /**
   * @param string $slug
   * @return $this
   */
  public function setSlug($slug)
  {
    $this->slug = $slug;
    return $this;
  }

  /**
   * @return string
   */
  public function getSlugSource()
  {
    return $this->getTitle();
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->getTitle() ?: 'New news';
  }
}