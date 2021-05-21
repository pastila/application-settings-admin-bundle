<?php

namespace AppBundle\Model\File;

use AppBundle\Util\FileSizeFormatter;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileStorage
{
  protected $fileDir;

  public function __construct ($fileStorageDir)
  {
    $this->fileDir = $fileStorageDir;
  }


  /**
   * @param FileAwareInterface $fileAware
   * @param UploadedFile $file
   * @return File
   * @throws \Exception
   */
  public function store (FileAwareInterface $fileAware, UploadedFile $file)
  {
    if (!is_dir($this->fileDir) && !@mkdir($this->fileDir, 0777, true))
    {
      throw new \Exception(sprintf('Failed to create %s. %s', $this->fileDir, error_get_last()['message']));
    }

    $ext = $file->getClientOriginalExtension();
    $fileAware->setFileSize($file->getSize());
    $fileAware->setFileExtension($ext);
    $filename = sprintf('%s.%s', uniqid(), $file->getClientOriginalName());
    $fileAware->setFile($filename);
    $fileAware->setOriginalFilename($file->getClientOriginalName());

    return $file->move($this->fileDir, $filename);
  }

  /**
   * @param FileAwareInterface $fileAware
   * @return File
   */
  public function getFile (FileAwareInterface $fileAware)
  {
    return new File($this->getPathname($fileAware));
  }

  /**
   * @param FileAwareInterface $fileAware
   * @return bool
   */
  public function isExists (FileAwareInterface $fileAware)
  {
    return is_file($this->getPathname($fileAware));
  }

  /**
   * @param FileAwareInterface $fileAware
   * @return string|null
   */
  public function getExtension (FileAwareInterface $fileAware)
  {
    return $fileAware->getFileExtension();
  }

  /**
   * @param FileAwareInterface $fileAware
   * @return int
   */
  public function getFileSize (FileAwareInterface $fileAware)
  {
    return FileSizeFormatter::formatSize($fileAware->getFileSize());
  }

  /**
   * @param FileAwareInterface $fileAware
   * @return string
   */
  public function getUrl (FileAwareInterface $fileAware)
  {
    if (!$this->isExists($fileAware))
    {
      return null;
    }

    return sprintf('/uploads/files/%s', $fileAware->getFile());
  }

  protected function getPathname (FileAwareInterface $fileAware)
  {
    $pathname = sprintf('%s/%s', $this->fileDir, $fileAware->getFile());
    return $pathname;
  }
}