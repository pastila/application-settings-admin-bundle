<?php

namespace AppBundle\Admin\Menu;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MenuSocialAdmin extends AbstractMenuAdmin
{
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('text', null, [
        'label' => 'Название'
      ])
      ->add('url', null, [
        'label' => 'Ссылка/путь'
      ])
      ->add('_action', null, [
        'actions' => [
          'edit' => [],
          'delete' => [],
          'move' => array(
            'template' => '@PixSortableBehavior/Default/_sort_drag_drop.html.twig',
            'enable_top_bottom_buttons' => false,
          ),
        ]
      ]);
  }

  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('text', TextType::class, [
        'label' => 'Название',
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add('url', TextType::class, [
        'label' => 'Адрес ссылки',
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 512,
          ]),
        ],
      ])
      ->add('teaser', 'Accurateweb\MediaBundle\Form\ImageType', [
        'required' => false,
        'label' => 'Изображение',
      ]);
  }
}