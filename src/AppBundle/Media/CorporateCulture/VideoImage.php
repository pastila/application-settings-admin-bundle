<?php

namespace AppBundle\Media\CorporateCulture;


use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\Image;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;

class VideoImage extends Image
{
  private $filterOptions;

  public function __construct($id, $resourceId, $options)
  {
    $this->filterOptions = $options;

    parent::__construct('videos/' . $id, $resourceId);
  }

  public function getThumbnailDefinitions()
  {
    return array(
      'preview' => new ThumbnailDefinition('preview', new FilterChain(array(
        array(
          'id' => 'crop',
          'options' => $this->getFilterOptions('crop'),
          'resolver' => new CropFilterOptionsResolver()
        ),
        array(
          'id' => 'resize', 'options' => array('size' => '394x223'),
        )
      ))),
      'home' => new ThumbnailDefinition('home', new FilterChain(array(
        array(
          'id' => 'crop',
          'options' => $this->getFilterOptions('crop'),
          'resolver' => new CropFilterOptionsResolver(['auto_crop' => true, 'auto_crop_aspect_ratio' => 334/228]),
        ),
        array(
          'id' => 'resize', 'options' => array('size' => '334x228'),
        )
      ))),
      'small' => new ThumbnailDefinition('small', new FilterChain(array(
        array(
          'id' => 'crop',
          'options' => $this->getFilterOptions('crop'),
          'resolver' => new CropFilterOptionsResolver(['auto_crop' => true, 'auto_crop_aspect_ratio' => 103/73]),
        ),
        array(
          'id' => 'resize', 'options' => array('size' => '163x110'),
        )
      )))
    );
  }

  protected function getFilterOptions($id, $default = array())
  {
    return isset($this->filterOptions[$id]) ? $this->filterOptions[$id] : $default;
  }
}