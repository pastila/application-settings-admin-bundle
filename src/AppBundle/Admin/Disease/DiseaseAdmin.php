<?php

namespace AppBundle\Admin\Disease;

use AppBundle\Entity\Disease\DiseaseCategory;
use Knp\Menu\ItemInterface as MenuItemInterface;
use RedCode\TreeBundle\Admin\AbstractTreeAdmin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class DiseaseAdmin
 * @package AppBundle\Admin\Disease
 */
class DiseaseAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('name')
      ->add('_action', null, [
        'actions' => [
          'edit' => [],
          'delete' => [],
        ]
      ]);
  }

  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('category', 'AppBundle\Form\Common\NestedTreeSuggestType', [
        'label' => 'Категория',
        'required' => true,
      ])
      ->add('name');
  }
}