<?php

namespace AppBundle\Admin\InsuranceCompany;

use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Form\DataTransformer\RegionToEntityTransformer;
use Doctrine\ORM\PersistentCollection;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\Form\Type\CollectionType;
use AppBundle\Entity\Company\InsuranceRepresentative;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
    $list->add('region');
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
    /** @var InsuranceCompanyBranch $subject */
    $subject = $this->getSubject();

    $form
      ->add('region')
      ->add('kpp', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add('representatives', CollectionType::class, [
        'by_reference' => false,
        'label' => 'Представители СМО:',
        'btn_add' => 'Добавить',
        'type_options' => [
          'delete' => true,
        ]
      ], [
        'edit' => 'inline',
        'inline' => 'table',
        'allow_add' => true,
        'allow_delete' => true,
        'sortable' => 'region',
        'admin_code' => 'main.admin.insurance_representative'
      ])
      ->add('published');

    $form->get('representatives')
      ->addModelTransformer(new CallbackTransformer(
        function ($collection)
        {
          return $collection;
        },
        function ($collection) use ($subject)
        {
          /**
           * @var PersistentCollection $collection
           */
          if ($collection instanceof PersistentCollection)
          {
            $collection->map(function ($value) use ($subject)
            {
              /**
               * @var InsuranceRepresentative $value
               */
              $value->setBranch($subject);
              return $value;
            });
          }

          return $collection;
        }
      ));
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
      ->add('region')
      ->add('published')
      ->add('kpp')
      ->add('representatives');
  }
}