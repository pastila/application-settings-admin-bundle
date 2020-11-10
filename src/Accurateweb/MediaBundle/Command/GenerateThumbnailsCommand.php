<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace Accurateweb\MediaBundle\Command;

use Accurateweb\ImagingBundle\Adapter\GdImageAdapter;
use Accurateweb\ImagingBundle\Filter\GdFilterFactory;
use Accurateweb\MediaBundle\Generator\ImageThumbnailGenerator;
use Accurateweb\MediaBundle\Model\Image\ImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class GenerateThumbnailsCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('media:thumbnails:generate')
      ->setDescription('Generate all registered thumbnails for images')
      ->setHelp('Generates all registered thumbnails for images')
      ->addArgument('entity', null, 'Entity');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);
    $iterator = $this
      ->getContainer()
      ->get('doctrine.orm.entity_manager')
      ->getRepository($input->getArgument('entity'))
      ->createQueryBuilder('m')
      ->getQuery()
      ->iterate()
    ;

    $mediaStorageProvider = $this->getContainer()->get('aw.media.storage.provider');
    $generator = new ImageThumbnailGenerator($mediaStorageProvider->getMediaStorage(null), new GdImageAdapter(), new GdFilterFactory());

    foreach ($iterator as $media)
    {
      $image = $media[0];

      if ($image instanceof ImageAwareInterface)
      {
        $image = $image->getImage();
      }

      if (!$image instanceof ImageInterface)
      {
        continue;
      }

      try
      {
        $io->note(sprintf('%s generated', $image->getResourceId()));
        $generator->generate($image);
      }
      catch (FileNotFoundException $e)
      {
        $io->error($e->getMessage());
      }

    }
  }
}