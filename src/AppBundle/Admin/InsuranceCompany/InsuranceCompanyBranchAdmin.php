<?php

namespace AppBundle\Admin\InsuranceCompany;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class InsuranceCompanyBranchAdmin
 * @package AppBundle\Admin\InsuranceCompany
 */
class InsuranceCompanyBranchAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $container = $this->getConfigurationPool()->getContainer();
    $router = $container->get('router');
    $route = $router->match($this->getRequest()->getPathInfo());

    if ($route['_route'] !== 'admin_app_company_insurancecompany_company_insurancecompanybranch_list')
    {
      $list->add('company');
    }
    $list->add('bossFullNameDative');
    $list->add('code', null, [
      'label' => 'Код СМО'
    ]);
    $list->add('region', null, [
      'route' => ['name' => '']
    ]);
    $list->add('published', null, [
      'label' => 'Публикация',
    ])
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
      ->add('code', IntegerType::class, [
        'required' => true,
        'label' => 'Код СМО',
        'help' => 'Уникальный номер СМО',
        'constraints' => [
          new NotBlank(),
          new Assert\Type('integer'),
        ]
      ])
      ->add('region', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ]
      ])
      ->add('kpp', null, [
        'required' => false,
        'constraints' => [
          new Length([
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
      ->add('bossFullNameDative', TextType::class, [
        'required' => true,
        'label' => 'ФИО руководителя',
        'help' => 'ФИО руководителя в дательном падеже для отправки обращения',
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 255,
          ]),
        ]
      ])
      ->add('representatives', 'Sonata\AdminBundle\Form\Type\CollectionType', [
        'by_reference' => false,
        'allow_add' => true,
        'allow_delete' => true,
        'required' => false,
        'entry_type' => 'AppBundle\Form\InsuranceCompany\InsuranceRepresentativeType',
      ], [
        'edit' => 'inline',
        'inline' => 'table',
      ])
      ->add('published');
  }


  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $container = $this->getConfigurationPool()->getContainer();
    $router = $container->get('router');
    $route = $router->match($this->getRequest()->getPathInfo());

    if ($route['_route'] !== 'admin_app_company_insurancecompany_company_insurancecompanybranch_list')
    {
      $filter->add('company');
    }
    $filter
      ->add('code')
      ->add('region')
      ->add('published')
      ->add('kpp')
      ->add('bossFullNameDative');
  }
}