<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace Accurateweb\MediaBundle\Twig;


use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\Storage\MediaStorageInterface;

class MediaExtension extends \Twig_Extension
{
  private $mediaStorage;

  public  function __construct(MediaStorageInterface $storage)
  {
    $this->mediaStorage = $storage;
  }

  public function getFunctions()
  {
    return array(
      new \Twig_SimpleFunction('image_thumbnail_url', array($this, 'getImageThumbnailUrl')),
      new \Twig_SimpleFunction('image_url', array($this, 'getImageUrl')),
      new \Twig_SimpleFunction('image_exists', array($this, 'imageExists'))
    );
  }

  /**
   * Выводит миниатюру изображения
   */
  public function getImageThumbnailUrl(ImageAwareInterface $imageAware, $imageId, $thumbnailId)
  {
    $image = $imageAware->getImage($imageId);

    $thumbnail = $image->getThumbnail($thumbnailId);

    $mediaResource = null;
    if ($thumbnail)
    {
      $mediaResource = $this->mediaStorage->retrieve($thumbnail);
    }

    if (!$mediaResource)
    {
      return null;
    }

    return $mediaResource->getUrl();
  }

  public function getImageUrl($imageAware, $imageId=null)
  {
    if(!$imageAware instanceof ImageAwareInterface)
    {
      return null;
    }

    $image = $imageAware->getImage($imageId);
    $mediaResource = null;

    if ($image)
    {
      $mediaResource = $this->mediaStorage->retrieve($image);
    }

    if (!$mediaResource)
    {
      return null;
    }

    return $mediaResource->getUrl();
  }

  public function imageExists(ImageAwareInterface $imageAware, $imageId=null)
  {
    $image = $imageAware->getImage($imageId);

    return $image && $this->mediaStorage->exists($image);
  }
}