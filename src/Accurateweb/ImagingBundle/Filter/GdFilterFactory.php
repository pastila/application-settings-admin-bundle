<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace Accurateweb\ImagingBundle\Filter;


class GdFilterFactory implements FilterFactoryInterface
{
  private $classMap;

  public function __construct()
  {
    $this->classMap = array(
      'resize' => 'Accurateweb\ImagingBundle\Filter\GD\ResizeFilter',
      'crop' => 'Accurateweb\ImagingBundle\Filter\GD\CropFilter'
    );
  }

  /**
   * @param $id
   * @param $options
   * @return ImageFilterInterface
   * @throws \Exception
   */
  public function create($id, array $options = array())
  {
    if (!isset($this->classMap[$id]))
    {
      throw new \Exception();
    }

    $filterClassName = $this->classMap[$id];

    return new $filterClassName($options);
  }
}