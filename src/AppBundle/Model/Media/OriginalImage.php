<?php


namespace AppBundle\Model\Media;


use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\Image;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;

class OriginalImage extends Image
{
  public function getThumbnailDefinitions ()
  {
    return [
      'preview' => new ThumbnailDefinition('preview', new FilterChain([
        ['id' => 'resize', 'options' => ['size' => '100x']],
      ])),
      'fullscreen' => new ThumbnailDefinition('fullscreen', new FilterChain([
      ]))
    ];
  }

  public function getThumbnail ($id)
  {
    $definitions = $this->getThumbnailDefinitions();

    if (!isset($definitions[$id]))
    {
      throw new \Exception(sprintf('Thumbnail "%s" is not defined', $id));
    }

    return new ConvertedThumbnail($id, $this);
  }
}