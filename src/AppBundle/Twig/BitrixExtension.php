<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Twig;

class BitrixExtension extends \Twig_Extension
{
  public function getFunctions()
  {
    return [
      new \Twig_SimpleFunction('bx_resize_img', [$this, 'bxResizeImage'])
    ];
  }

  public function bxResizeImage($file, $arSize, $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL, $bInitSizes = false,
    $arFilters = false, $bImmediate = false, $jpgQuality = false)
  {
    return \CFile::ResizeImageGet($file, $arSize, $resizeType, $bInitSizes,
      $arFilters, $bImmediate, $jpgQuality);
  }
}
