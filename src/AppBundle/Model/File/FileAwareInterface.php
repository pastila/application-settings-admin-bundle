<?php

namespace AppBundle\Model\File;

interface FileAwareInterface
{
  /**
   * @return string
   */
  public function getFile ();

  /**
   * @return integer
   */
  public function getFileSize ();

  /**
   * @return string|null
   */
  public function getFileExtension ();
  /**
   * @param  string $file
   */
  public function setFile ($file);

  /**
   * @param integer $size
   */
  public function setFileSize ($size);

  /**
   * @param string|null $ext
   */
  public function setFileExtension ($ext);

  /**
   * @return string
   */
  public function getOriginalFilename ();

  /**
   * @param $filename string
   */
  public function setOriginalFilename ($filename);
}