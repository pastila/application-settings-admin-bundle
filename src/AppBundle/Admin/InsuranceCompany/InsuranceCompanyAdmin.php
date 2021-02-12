<?php

namespace AppBundle\Admin\InsuranceCompany;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Validator\InsuranceCompany\InsuranceCompanyBranchPublished;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sonata\Form\Type\CollectionType;

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
        )
      ));
  }

  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->tab('СМО')
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
      ->add('published')
      ->end()
      ->end();

    $form
      ->tab('Филиалы')
      ->add('branches', CollectionType::class, [
        'by_reference' => false,
        'label' => 'Филиалы:',
        'btn_add' => false,
        'type_options' => [
          'delete' => false,
          'delete_options' => [
            'type_options' => [
              'mapped' => false,
              'required' => false,
            ]
          ]
        ]
      ], [
        'edit' => 'inline',
        'inline' => 'table',
        'sortable' => 'regions',
        'order' => 'DESC',
        'admin_code' => 'main.admin.insurance_company_branch'
      ])
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
   *
   */
  protected function attachInlineValidator()
  {
    $metadata = $this->validator->getMetadataFor($this->getClass());
    $metadata->addConstraint(new InsuranceCompanyBranchPublished());

    return parent::attachInlineValidator();
  }
}