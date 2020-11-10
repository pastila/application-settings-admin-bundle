<?php

namespace AppBundle\Admin\Number;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

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
        'label' => 'Заголовок',
      ])
      ->add('original', null, [
        'template' => '@AccuratewebMedia/Admin/image_list_field.html.twig',
        'label' => 'Изображение'
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

  protected function configureFormFields (FormMapper $form)
  {
    $form
      ->add('title', null, [
        'label' => 'Заголовок',
      ])
      ->add('description', null, [
        'label' => 'Описание',
      ])
      ->add('url', null, [
        'label' => 'url',
      ])
      ->add('urlText', null, [
        'label' => 'Текст ссылки',
      ])
      ->add('original', 'Accurateweb\MediaBundle\Form\ImageType', [
        'required' => false,
        'label' => 'Изображение',
      ])
    ;
  }

  /**
   * @param Number $object
   * @return string|void
   */
  public function toString ($object)
  {
    if (!$object->getId())
    {
      return 'Новое изображение';
    }

    return $object->getOriginal();
  }
}