<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace Accurateweb\MediaBundle\Generator;


use Accurateweb\ImagingBundle\Adapter\AdapterInterface;
use Accurateweb\ImagingBundle\Filter\FilterFactoryInterface;
use Accurateweb\ImagingBundle\Filter\FilterOptionsResolverInterface;
use Accurateweb\ImagingBundle\Image\Image;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Media\Storage\MediaStorageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;


class ImageThumbnailGenerator
{
  private $mediaStorage;

  private $adapter;

  private $filterFactory;

  public function __construct(MediaStorageInterface $mediaStorage, AdapterInterface $adapter,
    FilterFactoryInterface $filterFactory)
  {
    $this->mediaStorage = $mediaStorage;
    $this->adapter = $adapter;
    $this->filterFactory = $filterFactory;
  }

  /**
   * @param ImageInterface $media
   * @param null $id Thumbnail ID
   */
  public function generate(ImageInterface $media, $id=null)
  {
    $thumbnailDefinitions = $media->getThumbnailDefinitions();

    foreach ($thumbnailDefinitions as $definition)
    {
      /** @var $definition ThumbnailDefinition */
      if (null !== $id && $definition->getId() !== $id)
      {
        continue;
      }


      $filename = $this->mediaStorage->getOriginalFilePath($media);

      if (!is_file($filename))
      {
        throw new FileNotFoundException(sprintf('File %s not exists', $filename));
      }

      $mimeType = MimeTypeGuesser::getInstance()->guess($filename);

      if (preg_match('/svg/', $mimeType))
      {
        $thumbnail = new ImageThumbnail($definition->getId(), $media);
        $thumbnailPath = $this->mediaStorage->getPublicFilePath($thumbnail);
        $thumbnailDir = pathinfo($thumbnailPath, PATHINFO_DIRNAME);

        if (!is_dir($thumbnailDir))
        {
          @mkdir($thumbnailDir, 0777, true);
        }

        copy($filename, $thumbnailPath);
      }
      else
      {
        /** @var Image $image */
        $image = $this->adapter->loadFromFile($filename);

        if (!$image)
        {
          throw new \Exception(sprintf('Failed to load image %s', $filename));
        }

        $filterChain = $definition->getFilterChain();

        foreach ($filterChain as $filterDefinition)
        {
          $options = $filterDefinition['options'];

          if (isset($filterDefinition['resolver']))
          {
            $resolver = $filterDefinition['resolver'];

            if ($resolver instanceof FilterOptionsResolverInterface)
            {
              try
              {
                $options = $resolver->resolve($image, $options);
              }
              catch (\Exception $e)
              {
                //Filter is not configurable, so we skip it
                continue;
              }
            }
          }

          $filter = $this->filterFactory->create($filterDefinition['id'], $options);

          $filter->process($image);
          $image->refresh();
        }

        $thumbnail = new ImageThumbnail($definition->getId(), $media);
        $thumbnailPath = $this->mediaStorage->getPublicFilePath($thumbnail);
        $thumbnailDir = pathinfo($thumbnailPath, PATHINFO_DIRNAME);

        if (!is_dir($thumbnailDir))
        {
          @mkdir($thumbnailDir, 0777, true);
        }

        $this->adapter->save($image, $thumbnailPath);

        unset($image);
      }
    }
  }
}