<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Twig;

class MobileDetectExtension extends \Twig_Extension
{
  private $mobileDetect;

  public function __construct()
  {
    $this->mobileDetect = new \Mobile_Detect();
  }

  public function getFunctions()
  {
    return [
      new \Twig_SimpleFunction('is_mobile', [$this->mobileDetect, 'isMobile']),
      new \Twig_SimpleFunction('is_tablet', [$this->mobileDetect, 'isTablet']),
    ];
  }
}
