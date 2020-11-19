<?php

namespace AppBundle\Entity\Number;

use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Accurateweb\MediaBundle\Annotation as Media;

/**
 * @ORM\Table(name="s_numbers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Number\NumberRepository")
 */
class Number implements ImageAwareInterface, ImageInterface
{
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
  private $position = 0;

  /**
   * Заголовок
   *
   * @var string
   *
   * @ORM\Column(name="title", type="text", nullable=false)
   */
  private $title;

  /**
   * Описание
   *
   * @var string
   *
   * @ORM\Column(name="description", type="text", nullable=false)
   */
  private $description;

  /**
   * Описание
   *
   * @var string
   *
   * @ORM\Column(name="url", type="text", nullable=false)
   */
  private $url;

  /**
   * Описание
   *
   * @var string
   *
   * @ORM\Column(name="url_text", type="text", nullable=false)
   */
  private $urlText;

  /**
   * @var string
   *
   * @ORM\Column(name="original", type="string", length=255, nullable=true)
   * @Media\Image(id="original")
   */
  protected $original;

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
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * @param string $description
   *
   * @return string
   */
  public function setDescription($description)
  {
    $this->description = $description;

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
   *
   * @return string
   */
  public function setUrl($url)
  {
    $this->url = $url;

    return $this;
  }

  /**
   * @return string
   */
  public function getUrlText()
  {
    return $this->urlText;
  }

  /**
   * @param string $urlText
   *
   * @return string
   */
  public function setUrlText($urlText)
  {
    $this->urlText = $urlText;

    return $this;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->getId() ? $this->getTitle() : 'Новая запись';
  }

  /**
   * @return string
   */
  public function getOriginal()
  {
    return $this->original;
  }

  /**
   * @param $original
   * @return $this
   */
  public function setOriginal($original)
  {
    if (!is_null($original))
    {
      $this->original = $original;
    }

    return $this;
  }

  /**
   * @return bool|null
   */
  public function getIsImageSvg()
  {
    if (!is_null($this->original))
    {
      $array = explode(".", $this->original);
      return (end($array) === 'svg') ? true : false;
    }
    return false;
  }

  /**
   * @return string
   */
  public function getResourceId()
  {
    return $this->getOriginal();
  }

  /**
   * @param $resourceId string
   */
  public function setResourceId($resourceId)
  {
    $this->setOriginal($resourceId);
  }

  /**
   * Get thumbnail definitions
   *
   * @return string[]
   */
  public function getThumbnailDefinitions()
  {
    return [
      'preview' => new ThumbnailDefinition('preview', new FilterChain([
        [
          'id' => 'crop',
          'options' => [],
          'resolver' => new CropFilterOptionsResolver()
        ],
        [
          'id' => 'resize',
          'options' => ['size' => '80x80']
        ]
      ])),
      'small' => new ThumbnailDefinition('small', new FilterChain([
        [
          'id' => 'resize',
          'options' => ['size' => 'x130']
        ]
      ]))
    ];
  }

  /**
   * @param string $id
   * @return ImageThumbnail
   * @throws \Exception
   */
  public function getThumbnail($id)
  {
    $definitions = $this->getThumbnailDefinitions();

    $found = false;
    foreach ($definitions as $definition)
    {
      if ($definition->getId() == $id)
      {
        $found = true;
        break;
      }
    }

    if (!$found)
    {
      throw new \Exception('Image thumbnail definition not found');
    }

    return new ImageThumbnail($id, $this);
  }

  /**
   * @param $id
   * @return ImageInterface
   */
  public function getImage($id = null)
  {
    return $this;
  }

  /**
   * @param ImageInterface $image
   * @return $this|mixed
   */
  public function setImage(ImageInterface $image)
  {
    $this->setResourceId($image->getResourceId());
    return $this;
  }

  /**
   * @param $id
   * @return mixed
   */
  public function getImageOptions($id)
  {
    return null;
  }

  public function setImageOptions($id)
  {
  }

  public function getOriginalImage ()
  {
    return $this->getImage();
  }

  public function setOriginalImage ($image)
  {
    return $this->setImage($image);
  }
}
