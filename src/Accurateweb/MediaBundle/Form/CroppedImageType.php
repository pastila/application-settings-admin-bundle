<?php

namespace Accurateweb\MediaBundle\Form;

use Accurateweb\MediaBundle\Model\Image\CropableImageAwareInterface;
use Accurateweb\MediaBundle\Model\Media\MediaManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CroppedImageType extends AbstractType
{
  protected $mediaManager;

  public function __construct (MediaManager $mediaManager)
  {
    $this->mediaManager = $mediaManager;
  }

  public function buildView (FormView $view, FormInterface $form, array $options)
  {
    $entity = $form->getParent()->getData();

    if (!$entity instanceof CropableImageAwareInterface)
    {
      throw new \Exception('Entity should implements CropableImageAwareInterface');
    }

    $image = $entity->getImage($options['image_id']);
    $imageUrl = null;
    $thumbUrl = null;

    if ($image)
    {
      $storage = $this->mediaManager->getMediaStorage($image);
      $rs = $storage->retrieve($image);
      $imageUrl = $rs ? $rs->getUrl() : null;

      if ($options['thumbnail'] && $image->getThumbnail($options['thumbnail']))
      {
        $thumb = $storage->retrieve($image->getThumbnail($options['thumbnail']));

        if ($thumb)
        {
          $thumbUrl = $thumb->getUrl();
        }
      }
    }

    $view->vars = array_replace($view->vars, array(
      'type' => 'file',
      'value' => '',
      'url' => $imageUrl,
      'thumb' => $thumbUrl,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function finishView (FormView $view, FormInterface $form, array $options)
  {
    /** @var CropableImageAwareInterface $entity */
    $entity = $form->getParent()->getData();
    $setSelect = false;

    if ($entity->getCrop())
    {
      $setSelect = $entity->getCrop();
    }

    $view->vars['multipart'] = true;
    $view->vars['options'] = [
      'crop' => [
        'aspectRatio' => $options['aspectRatio'],
        'boxWidth' => $options['boxWidth'],
        'boxHeight' => $options['boxHeight'],
        'setSelect' => $setSelect,
      ],
      'entity' => [
        'class' => get_class($entity),
        'id' => $entity->getId(),
      ],
    ];

    $view->vars['thumbnail'] = $options['thumbnail'];
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'compound' => false,
      'empty_data' => null,
      'boxWidth' => 500,
      'boxHeight' => 0,
      'aspectRatio' => 0,
      'thumbnail' => null,
      'image_id' => null
    ));
  }

  public function getBlockPrefix ()
  {
    return 'aw_crop_media_image';
  }
}