<?php

namespace AppBundle\EventListener;

use AppBundle\Model\File\FileAwareInterface;
use AppBundle\Model\File\FileStorage;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadListener implements EventSubscriber
{
  private $fileStorage;

  public function __construct (FileStorage $fileStorage)
  {
    $this->fileStorage = $fileStorage;
  }

  public function prePersist (LifecycleEventArgs $event)
  {
    $entity = $event->getEntity();

    if ($entity instanceof FileAwareInterface
      && $entity->getFile() instanceof UploadedFile
    )
    {
      $this->fileStorage->store($entity, $entity->getFile());
    }
  }

  public function preUpdate (LifecycleEventArgs $event)
  {
    $entity = $event->getEntity();

    if ($entity instanceof FileAwareInterface
      && $entity->getFile() instanceof UploadedFile
    )
    {
      $this->fileStorage->store($entity, $entity->getFile());
    }
  }

  public function getSubscribedEvents ()
  {
    return [
      Events::prePersist,
      Events::preUpdate,
    ];
  }

}