<?php


namespace AppBundle\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use NewsBundle\Model\News as Base;

/**
 * Class News
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Common\NewsRepository")
 * @ORM\Table()
 */
class News extends Base
{
  public function setCrop ($x, $y, $x1, $y1)
  {
    $opts = $this->getTeaserImageOptions();

    if (!is_array($opts))
    {
      $opts = [];
    }

    $opts['crop'] = [
      'left' => $x,
      'width' => $x1 - $x,
      'top' => $y,
      'height' => $y1 - $y
    ];

    $this->setTeaserImageOptions($opts);
    return $this;
  }

  public function getCrop ()
  {
    $opts = $this->getTeaserImageOptions();

    if (isset($opts['crop']))
    {
      return [
        $opts['crop']['left'],
        $opts['crop']['top'],
        $opts['crop']['left'] + $opts['crop']['width'],
        $opts['crop']['top'] + $opts['crop']['height'],
      ];
    }

    return null;
  }
}