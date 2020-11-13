<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Media\Transformation;

use Accurateweb\ImagingBundle\Filter\CropFilterOptionsResolver;
use Accurateweb\ImagingBundle\Filter\FilterChain;
use Accurateweb\MediaBundle\Model\Image\Image;
use Accurateweb\MediaBundle\Model\Thumbnail\ThumbnailDefinition;

class TeamMemberPhoto extends Image
{
  private $filterOptions;

  public function __construct($id, $resourceId, $options)
  {
    $this->filterOptions = $options;

    parent::__construct('transformation-team-members/'.$id, $resourceId);
  }

  public function getThumbnailDefinitions()
  {
    return [
      '113x113' => new ThumbnailDefinition('113x113', new FilterChain(array(
        array(
          'id' => 'crop',
          'options' => $this->getFilterOptions('crop'),
          'resolver' => new CropFilterOptionsResolver()
        ),
        array(
          'id' => 'resize', 'options' => array('size' => '113x113'),
        )
      ))),
      'preview' => new ThumbnailDefinition('preview', new FilterChain([
        [
          'id' => 'crop',
          'options' => $this->getFilterOptions('crop'),
          'resolver' => new CropFilterOptionsResolver(),
        ],
        [
          'id' => 'crop',
          'options' => [], //$this->getFilterOptions('crop'),
          'resolver' => new CropFilterOptionsResolver([
            'auto_crop_aspect_ratio' => 0.77,
          ]),
        ],
        [
          'id' => 'resize', 'options' => ['size' => '254x330'],
        ]
      ])),
    ];
  }

  protected function getFilterOptions($id, $default=array())
  {
    return isset($this->filterOptions[$id]) ? $this->filterOptions[$id] : $default;
  }
}