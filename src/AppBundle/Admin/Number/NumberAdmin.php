<?php

namespace AppBundle\Admin\Number;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NumberAdmin extends AbstractAdmin
{
  protected $translationDomain = 'messages';

  protected $datagridValues = array(
    '_page' => 1,
    '_sort_order' => 'ASC',
    '_sort_by' => 'position',
  );

  public function getFilterParameters()
  {
    $filter = parent::getFilterParameters();

    return $filter;
  }

  public function configure()
  {
    $this->setTemplate('list', 'SonataAdminBundle:CRUD:list_sortable.html.twig');
  }

  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
  }

  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('title', null, [
        'label' => 'Число',
      ])
      ->add('description', null, [
        'label' => 'Подпись'
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
      ->add('teaser', 'Accurateweb\MediaBundle\Form\ImageType', [
        'required' => false,
        'label' => 'Изображение',
      ])
      ->add('title', TextType::class, [
        'label' => 'Число',
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add('description', TextareaType::class, [
        'label' => 'Подпись',
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 512,
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
      ->add('urlText', TextType::class, [
        'label' => 'Текст ссылки',
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 255,
          ]),
        ],
      ]);
  }
}