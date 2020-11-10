<?php

namespace Accurateweb\MediaBundle\Command;

use Accurateweb\MediaBundle\Generator\ImageThumbnailGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class RegenerateThumbnailsCommand extends ContainerAwareCommand
{
  protected function configure ()
  {
    $this
      ->setName('media:thumbnails:regenerate')
      ->setDescription('Generate all registered thumbnails for all images');
  }

  protected function execute (InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $classNames = $entityManager->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
    $mediaStorageProvider = $this->getContainer()->get('aw.media.storage.provider');
    $generator = new ImageThumbnailGenerator($mediaStorageProvider->getMediaStorage(null), $this->getContainer()->get('aw_imaging.adapter.gd'), $this->getContainer()->get('aw_imaging.filter.factory.gd'));
    $images = [];
    $imageAwares = [];

    foreach ($classNames as $className)
    {
      $metaData = $entityManager->getClassMetadata($className);

      if ($metaData->getReflectionClass()->isAbstract())
      {
        continue;
      }

      if ($metaData->getReflectionClass()->implementsInterface('Accurateweb\MediaBundle\Model\Image\ImageAwareInterface'))
      {
        $imageAwares[] = $className;
      }
      elseif ($metaData->getReflectionClass()->implementsInterface('Accurateweb\MediaBundle\Model\Media\ImageInterface'))
      {
        $images[] = $className;
      }
    }

    $io->note(sprintf('Found %s image classes, %s imageAware classes', count($images), count($imageAwares)));

    foreach ($imageAwares as $imageAwareClass)
    {
      $io->note('Generate '.$imageAwareClass);

      $iterator = $this
        ->getContainer()
        ->get('doctrine.orm.entity_manager')
        ->getRepository($imageAwareClass)
        ->createQueryBuilder('m')
        ->getQuery()
        ->iterate()
      ;

      foreach ($iterator as $item)
      {
        $image = $item[0];
        $image = $image->getImage();

        if (!$image)
        {
          continue;
        }

        try
        {
          $generator->generate($image);
          $io->text(sprintf('%s', $image->getResourceId()));
        }
        catch (FileNotFoundException $e)
        {
          $io->error($e->getMessage());
        }
      }

      $entityManager->clear($imageAwareClass);
    }

    foreach ($images as $imageClass)
    {
      $io->note('Generate '.$imageClass);

      $iterator = $this
        ->getContainer()
        ->get('doctrine.orm.entity_manager')
        ->getRepository($imageClass)
        ->createQueryBuilder('m')
        ->getQuery()
        ->iterate()
      ;

      foreach ($iterator as $item)
      {
        $image = $item[0];

        try
        {
          $generator->generate($image);
          $io->text(sprintf('%s', $image->getResourceId()));
        }
        catch (FileNotFoundException $e)
        {
          $io->error($e->getMessage());
        }
      }

      $entityManager->clear($imageClass);
    }
  }
}