<?php

namespace Accurateweb\MediaBundle\Command;

use Accurateweb\ImagingBundle\Adapter\GdImageAdapter;
use Accurateweb\ImagingBundle\Filter\GdFilterFactory;
use Accurateweb\MediaBundle\Generator\ImageThumbnailGenerator;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;

class GenerateImagesCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('media:images:generate')
      ->setDescription('Generate all registered images')
      ->setHelp('From var to web')
      ->addArgument('entity', null, 'Entity');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $iterator = $this
      ->getContainer()
      ->get('doctrine.orm.entity_manager')
      ->getRepository($input->getArgument('entity'))
      ->createQueryBuilder('m')
      ->getQuery()
      ->iterate()
    ;

    $mediaStorageProvider = $this->getContainer()->get('aw.media.storage.provider');

    foreach ($iterator as $media)
    {
      if (!$media[0] instanceof ImageInterface || !$media[0]->getResourceId())
      {
        continue;
      }

      $storage = $mediaStorageProvider->getMediaStorage($media[0]);
      $original = $storage->getOriginalFilePath($media[0]);
      $web = $storage->getPublicFilePath($media[0]);

      if (file_exists($original) && !file_exists($web))
      {
        $f = new File($original);
        $storage->copy($media[0], $storage->getUploadsDir(), $f);
        $output->writeln(sprintf('Copy original -> public (%s)', $original));
      }
      elseif (file_exists($web) && !file_exists($original))
      {
        $f = new File($web);
        $storage->copy($media[0], $storage->getOriginalsDir(), $f);
        $output->writeln(sprintf('Copy public -> original (%s)', $web));
      }
    }
  }
}