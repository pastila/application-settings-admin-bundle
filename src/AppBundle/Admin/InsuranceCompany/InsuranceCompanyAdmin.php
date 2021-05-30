<?php

namespace AppBundle\Admin\InsuranceCompany;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sonata\AdminBundle\Route\RouteCollection;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

/**
 * Class InsuranceCompanyAdmin
 * @package AppBundle\Admin\Company
 */
class InsuranceCompanyAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('kpp')
      ->add('name')
      ->add('published', null, [
        'label' => 'Публикация',
      ])
      ->add('_action', null, array(
        'label' => 'Действия',
        'actions' => array(
          'edit' => array(),
          'delete' => array(),
          'branches' => [
            'template' => '@App/admin/branches/buttons.html.twig',
          ],
        )
      ));
  }

  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->tab('Основное')
      ->add('logo', 'Accurateweb\MediaBundle\Form\ImageType', [
        'required' => false,
        'label' => 'Логотип',
      ])
      ->add("name", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("kpp", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add('phones', TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add('published')
      ->end()
      ->end();
  }

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('kpp')
      ->add('name')
      ->add('published');
  }

  /**
   * @param RouteCollection $collection
   */
  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->add('branches');
  }

  /**
   * @param MenuItemInterface $menu
   * @param string $action
   * @param AdminInterface|null $childAdmin
   */
  protected function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
  {
    parent::configureTabMenu($menu, $action, $childAdmin);

    if ($this->getSubject() && $action === 'edit')
    {
      $menu->addChild('branches', [
        'uri' => $this->getConfigurationPool()
          ->getContainer()->get('router')
          ->generate('admin_app_company_insurancecompany_company_insurancecompanybranch_list', [
            'id' => $this->getSubject()->getId()
          ]),
        'label' => 'Список филиалов СМО',
      ]);
    }
  }
}