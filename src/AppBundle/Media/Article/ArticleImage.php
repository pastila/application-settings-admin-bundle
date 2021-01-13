<?php


namespace AppBundle\Media\Article;


use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\Image;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;

class ArticleImage extends Image
{
  private $filterOptions;

  public function __construct($id, $resourceId, $options=[])
  {
    $this->filterOptions = $options;

    parent::__construct($id, $resourceId);
  }

  public function getThumbnailDefinitions ()
  {
    return [
      'main_lg' => new ThumbnailDefinition('main_lg', new FilterChain([
        [
          'id' => 'resize',
          'options' => ['size' => 'x400']
        ]
      ]))
    ];
  }
}