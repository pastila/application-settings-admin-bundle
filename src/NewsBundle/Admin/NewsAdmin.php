<?php
/**
 * Created by PhpStorm.
 * User: eobuh
 * Date: 24.05.2018
 * Time: 18:17
 */

namespace NewsBundle\Admin;

use NewsBundle\Model\News;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsAdmin extends AbstractAdmin
{
  public function configureListFields(ListMapper $list)
  {
    $list
      ->add("title")
      ->add("createdAt", 'datetime', [
        'format' => 'd.m.Y H:i',
      ])
      ->add("publishedAt", 'datetime', [
        'format' => 'd.m.Y',
      ])
      ->add('_action', null, array(
        'actions' => array(
          'edit' => array(),
          'delete' => array(),
        )
      ));
  }

  public function configureFormFields(FormMapper $form)
  {
    $form
      ->tab("Основные")
        ->add("title")
        ->add("announce", 'AppBundle\Form\Common\TinyMceType', [
          'attr' => [
            'class' => 'tinymce',
            'tinymce' => '{"theme":"simple"}',
            'data-theme' => 'bbcode',
          ],
          'required' => true
        ])
        ->add("isPublished")
        ->add("publishedAt", 'Sonata\CoreBundle\Form\Type\DatePickerType', [
          'format' => 'd MMMM yyyy',
          'view_timezone' => 'UTC',
          'model_timezone' => 'UTC',
        ])
      ->end()
      ->end()
      ->tab("Содержание")
        ->add('text', 'AppBundle\Form\Common\TinyMceType', [
            'attr' => [
              'class' => 'tinymce',
              'tinymce' => '{"theme":"simple"}',
              'data-theme' => 'bbcode',
            ],
            'required' => true
          ]
        )
      ->end()
      ->end()
      ->tab("Изображение")
        ->add('teaser', 'Accurateweb\MediaBundle\Form\ImageType', [
          'required' => false,
          'label' => 'Изображение',
        ])
      ->end()
      ->end()
    ;
  }

  /**
   * @param News $object
   */
  public function prePersist($object)
  {
    if(!$object->getCreatedAt())
    {
      $object->setCreatedAt(new \DateTime());
    }
  }
}