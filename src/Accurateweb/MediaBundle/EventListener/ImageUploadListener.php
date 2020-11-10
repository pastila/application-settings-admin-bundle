<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace Accurateweb\MediaBundle\EventListener;


use Accurateweb\MediaBundle\Generator\ImageThumbnailGenerator;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Gallery\MediaGalleryAwareInterface;
use Accurateweb\MediaBundle\Service\ImageUploader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ImageUploadListener
{
  private $uploader;

  private $annotationReader;

  private $propertyAccessor;
  private $imageThumbnailGenerator;

  public function __construct (
    ImageUploader $uploader,
    AnnotationReader $annotationReader,
    ImageThumbnailGenerator $imageThumbnailGenerator
  )
  {
    $this->uploader = $uploader;
    $this->annotationReader = $annotationReader;
    $this->imageThumbnailGenerator = $imageThumbnailGenerator;
    $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

  }

  public function prePersist (LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();

    $this->uploadFile($entity, $args->getObjectManager());
  }

  public function preUpdate (PreUpdateEventArgs $args)
  {
    $entity = $args->getEntity();

    $this->uploadFile($entity, $args->getObjectManager());
  }

  private function uploadFile ($object, ObjectManager $om)
  {
    if (!$object instanceof ImageAwareInterface)
    {
      return;
    }

    $meta = $om->getClassMetadata(get_class($object));
    $rc = $meta->getReflectionClass();
    $props = $rc->getProperties();

    foreach ($props as $prop)
    {
      $ann = $this->annotationReader->getPropertyAnnotation($prop, '\\Accurateweb\\MediaBundle\\Annotation\\Image');
      if ($ann)
      {
        $file = $meta->getReflectionProperty($prop->name)->getValue($object);

        $image = $this->propertyAccessor->getValue($object, $prop->name . '_image');

        if (!$image instanceof ImageInterface)
        {
          continue;
        }

        // only upload new files
        if (!$file instanceof UploadedFile)
        {
          continue;
        }

        if ($image instanceof MediaGalleryAwareInterface)
        {
          $resourceId = implode('/', [
            $image->getGalleryProviderId(),
            $image->getId(),
            md5(uniqid()) . ($file->guessExtension() ? '.' . $file->guessExtension() : '')
          ]);
        }
        else
        {
          $resourceId = implode('/', [
            $image->getId(),
            md5(uniqid()) . ($file->guessExtension() ? '.' . $file->guessExtension() : '')
          ]);
        }

        $image->setResourceId($resourceId);
        $this->uploader->upload($image, $file);
        $this->propertyAccessor->setValue($object, $prop->name . '_image', $image);

        $this->imageThumbnailGenerator->generate($image, null);
      }
    }
  }

//  private function uploadFile ($entity)
//  {
//    if (!$entity instanceof ImageAwareInterface)
//    {
//      return;
//    }
//
//    $image = $entity->getImage();
//
//    if (!$image instanceof ImageInterface)
//    {
//      return;
//    }
//
//    $file = $image->getResourceId();
//
//    // only upload new files
//    if (!$file instanceof UploadedFile)
//    {
//      return;
//    }
//
//    $resourceId = implode('/', [
//      $image->getId(),
//      md5(uniqid()) . ($file->guessExtension() ? '.' . $file->guessExtension() : '')
//    ]);
//
//    $image->setResourceId($resourceId);
//
//    $this->uploader->upload($image, $file);
//
//    $entity->setImage($image);
//  }
}