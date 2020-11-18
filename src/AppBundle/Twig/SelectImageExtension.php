<?php

namespace AppBundle\Twig;
use AppBundle\Entity\Number;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SelectImageExtension extends \Twig_Extension
{
  public function getFunctions ()
  {
    return array(
      'check_image' => new \Twig_SimpleFunction('check_image', array($this, 'checkImage'))
    );
  }

  /**
   * @param $content
   * @return |null
   */
  public function checkImage ($content)
  {
    /**
     * @var Number $content
     */
    $file = $content->getOriginal();
    if (!strripos($file, '.svg'))
    {
      return true;
    }

    return false;
  }
}