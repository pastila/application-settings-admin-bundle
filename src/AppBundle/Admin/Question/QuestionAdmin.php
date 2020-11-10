<?php

namespace AppBundle\Admin\Question;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionAdmin extends AbstractAdmin
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
      ->add('question', null, [
        'label' => 'Вопрос',
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
      ->add('question', null, [
        'label' => 'Вопрос',
      ])
      ->add('answer', 'AppBundle\Form\Common\TinyMceType', [
        'required' => true,
        'label' => 'Ответ',
        'constraints' => [
          new NotBlank(),
        ],
      ])
    ;
  }
}