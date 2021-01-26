<?php


namespace AppBundle\Model\Media;


use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\Image;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;

class CompanyLogo extends Image
{
  private $filterOptions;

  public function __construct($id, $resourceId, $options=array())
  {
    $this->filterOptions = $options;

    parent::__construct($id, $resourceId);
  }

  /**
   * Get thumbnail definitions
   *
   * @return string[]
   */
  public function getThumbnailDefinitions()
  {
    return [
      'small' => new ThumbnailDefinition('small', new FilterChain([
        [
          'id' => 'resize',
          'options' => ['size' => 'x130']
        ]
      ]))
    ];
  }

}