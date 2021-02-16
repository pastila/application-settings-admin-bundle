<?php

namespace AppBundle\Admin\Disease;

use AppBundle\Entity\Disease\CategoryDisease;
use Knp\Menu\ItemInterface as MenuItemInterface;
use RedCode\TreeBundle\Admin\AbstractTreeAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CategoryAdmin
 * @package AppBundle\Admin\Disease
 */
class CategoryAdmin extends AbstractTreeAdmin
{
  public function __construct($code, $class, $baseControllerName, $treeTextField)
  {
    parent::__construct($code, $class, $baseControllerName, $treeTextField);
    unset($this->listModes['list']);
    unset($this->listModes['mosaic']);
  }

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

  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('name', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ]);
  }

  public function configureActionButtons($action, $object = null)
  {
    $actions = parent::configureActionButtons($action, $object);

    if (isset($actions['create']))
    {
      unset($actions['create']);
    }

    return $actions;
  }

  protected function configureRoutes(RouteCollection $collection)
  {
    parent::configureRoutes($collection);
    $collection->add('reorder-all');
    $collection->add('move', 'move');
    $collection->add('tree');
  }

  protected function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
  {
    parent::configureTabMenu($menu, $action, $childAdmin);

    if ($action === 'list')
    {
      $menu->addChild('Отсортировать категории', [
        'uri' => $this->generateUrl('reorder-all')
      ]);
    }
  }

  /**
   * @param CategoryDisease $object
   * @throws ModelManagerException
   */
  public function preRemove($object)
  {
    $root = $object->getTreeRoot();

    if ($root->getId() == $object->getId())
    {
      throw new ModelManagerException(sprintf('Невозможно удалить корневую категорию'));
    }

    parent::preRemove($object);
  }
}