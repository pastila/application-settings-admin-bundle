<?php

namespace AppBundle\Entity\Menu;


use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;
use Doctrine\ORM\Mapping as ORM;
use Accurateweb\MediaBundle\Annotation as Media;

/**
 * @ORM\Table(name="s_menu_social")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Menu\MenuSocialRepository")
 */
class MenuSocial extends AbstractMenu implements ImageAwareInterface, ImageInterface
{
  /**
   * @var string
   *
   * @ORM\Column(name="original", type="string", length=255, nullable=true)
   * @Media\Image(id="original")
   */
  protected $original;


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
      'icon' => new ThumbnailDefinition('icon', new FilterChain([
        [
          'id' => 'resize',
          'options' => ['size' => 'x35']
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
