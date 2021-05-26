<?php

namespace AppBundle\Twig;

use AppBundle\Model\File\FileStorage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileExtension extends AbstractExtension
{
  private $fileStorage;

  public function __construct (FileStorage $fileStorage)
  {
    $this->fileStorage = $fileStorage;
  }

  public function getFunctions ()
  {
    return [
      new TwigFunction('isFileExists', [$this->fileStorage, 'isExists']),
      new TwigFunction('getFileExtension', [$this->fileStorage, 'getExtension']),
      new TwigFunction('getFileUrl', [$this->fileStorage, 'getUrl']),
      new TwigFunction('getFileSize', [$this->fileStorage, 'getFileSize']),
    ];
  }
}