<?php


namespace AppBundle\Model\Media;


use Accurateweb\MediaBundle\Model\Media\ImageInterface;
use Accurateweb\MediaBundle\Model\Thumbnail\ImageThumbnail;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;

class ConvertedThumbnail extends ImageThumbnail
{
  private $ext;

  public function __construct ($id, ImageInterface $image)
  {
    parent::__construct($id, $image);
    $definitions = $image->getThumbnailDefinitions();
    /** @var ThumbnailDefinition $definition */
    $definition = $definitions[$id];

    foreach ($definition->getFilterChain() as $item)
    {
      if ($item['id'] == 'store.convert')
      {
        $options = isset($item['options'])?$item['options']:[];
        $this->ext = isset($options['format'])?$options['format']:null;
      }
      elseif ($item['id'] == 'convert')
      {
        $options = isset($item['options'])?$item['options']:[];
        $this->ext = isset($options['to'])?$options['to']:null;
      }
    }
  }
}