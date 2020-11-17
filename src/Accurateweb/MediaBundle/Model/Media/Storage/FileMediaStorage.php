<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace Accurateweb\MediaBundle\Model\Media\Storage;


use Accurateweb\MediaBundle\Model\Media\MediaInterface;
use Accurateweb\MediaBundle\Model\Media\Resource\MediaResource;
use Accurateweb\MediaBundle\Model\Media\Resource\MediaResourceInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException; //добавил

class FileMediaStorage implements MediaStorageInterface
{
  /**
   * Каталог сохранения оригинальных файлов
   *
   * @var string
   */
  private $originalsDir;

  /**
   * Каталог сохранения файлов, доступных для загрузки
   *
   * @var string
   */
  private $uploadsDir;

  /**
   * @var string
   */
  private $urlPrefix;

  public function __construct($uploadsDir, $originalsDir=null, $urlPrefix='')
  {
    $this->uploadsDir = $uploadsDir;
    $this->originalsDir = $originalsDir;
    $this->urlPrefix = $urlPrefix;
  }

  public function getUploadsDir()
  {
    return $this->uploadsDir;
  }

  public function getOriginalsDir()
  {
    return $this->originalsDir;
  }


  public function store(MediaInterface $media, File $file)
  {
    $this->copy($media, $this->originalsDir, $file);
    $this->copy($media, $this->uploadsDir, $file);
  }

  /**
   * @param MediaInterface $media
   * @return MediaResourceInterface
   */
  public function retrieve(MediaInterface $media)
  {
    $resource = new MediaResource($media, $this->uploadsDir, $this->urlPrefix);

    if (!$resource->fileExists())
    {
      $resource = null;
    }

    return $resource;
  }

  public function getOriginalFilePath(MediaInterface $media)
  {
    return $this->originalsDir.'/'.$media->getResourceId();
  }

  public function getPublicFilePath(MediaInterface $media)
  {
    return $this->uploadsDir.'/'.$media->getResourceId();
  }

  public function copy(MediaInterface $media, $baseDir, File $file)
  {
    if (!realpath($baseDir))
    {
      $dir = realpath(dirname($baseDir));

      if (!$dir)
      {
        throw new FileException(sprintf('Unable to create the "%s" directory', $baseDir));
      }

      $baseDir = $dir . pathinfo($baseDir, PATHINFO_BASENAME);
    }
    else
    {
      $baseDir = realpath($baseDir);
    }

    $path = $baseDir.'/'.$media->getResourceId();
    $directory = pathinfo($path, PATHINFO_DIRNAME);

    if (!is_dir($directory))
    {
      if (false === @mkdir($directory, 0777, true) && !is_dir($directory))
      {
        $error = error_get_last();
        throw new FileException(sprintf('Unable to create the "%s" directory. %s', $directory, strip_tags($error['message'])));
      }
    }
//    elseif (!is_writable($directory))
//    {
//      throw new FileException(sprintf('Unable to write in the "%s" directory', $directory));
//    }

    if (!@copy($file->getPathname(), $path))
    {
      $error = error_get_last();
      throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $file->getPathname(), $path, strip_tags($error['message'])));
    }
  }

  public function exists(MediaInterface $media)
  {
    return null !== $this->retrieve($media);
  }

  public function remove(MediaInterface $media)
  {
    @unlink($this->getOriginalFilePath($media));
    @unlink($this->getPublicFilePath($media));
  }
}