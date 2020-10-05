<?php

namespace AppBundle\Twig;

/**
 * Class NumberExtension
 * @package AppBundle\Twig
 */
class NumberExtension extends \Twig_Extension
{
  public function getFilters()
  {
    return [
      new \Twig_SimpleFilter('trim_number', [$this, 'trimNumber'])
    ];
  }

  /**
   * @param $number
   * @return int
   */
  public function trimNumber($number)
  {
    return trim(rtrim($number, '0'), '.');
  }
}
