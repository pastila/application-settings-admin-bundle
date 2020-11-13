<?php
/**
 * Created by PhpStorm.
 * User: eobuh
 * Date: 04.06.2018
 * Time: 11:38
 */

namespace AppBundle\Media\Text;

use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\Image;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;

class NewsImage extends Image
{
  private $filterOptions;
  
  public function __construct($id, $resourceId, $options)
  {
    $this->filterOptions = $options;
    
    parent::__construct('news/'.$id, $resourceId);
  }
  
  public function getThumbnailDefinitions()
  {
    return array(
      'preview' => new ThumbnailDefinition('preview', new FilterChain(array(
        array(
          'id' => 'crop',
          'options' => $this->getFilterOptions('crop'),
          'resolver' => new CropFilterOptionsResolver(),
        ),
        array(
          'id' => 'resize', 'options' => array('size' => '188x144'),
        )
      )))
    );
  }
  
  protected function getFilterOptions($id, $default=array())
  {
    return isset($this->filterOptions[$id]) ? $this->filterOptions[$id] : $default;
  }
}