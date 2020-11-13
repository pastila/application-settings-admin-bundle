<?php


namespace AppBundle\Entity\Common;

use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;
use Doctrine\ORM\Mapping as ORM;
use NewsBundle\Model\News as Base;

/**
 * Class News
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Common\NewsRepository")
 * @ORM\Table()
 */
class News extends Base implements ImageAwareInterface, ImageInterface
{
  /**
   * @return string
   */
  public function getResourceId()
  {
    return $this->getTeaser();
  }

  /**
   * @param $resourceId string
   */
  public function setResourceId($resourceId)
  {
    $this->setTeaser($resourceId);
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
      'main_lg' => new ThumbnailDefinition('main_lg', new FilterChain([
        [
          'id' => 'resize',
          'options' => ['size' => 'x245']
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

  public function getTeaserImage ()
  {
    return $this->getImage();
  }

  public function setTeaserImage ($image)
  {
    return $this->setImage($image);
  }
}