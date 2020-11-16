<?php

namespace AppBundle\Admin\Menu;

use AppBundle\Entity\Menu\AbstractMenu;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Validator\ErrorElement;

abstract class AbstractMenuAdmin extends AbstractAdmin
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
      ->add('text', null, [
        'label' => 'Текст'
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
      ->add('text', null, [
        'label' => 'Текст'
      ])
      ->add('url', null, [
        'label' => 'Ссылка/путь'
      ]);
  }
}