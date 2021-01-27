<?php

namespace AppBundle\Admin\Organization;

use AppBundle\Entity\Organization\OrganizationStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class OrganizationAdmin
 * @package AppBundle\Admin\Organization
 */
class OrganizationAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('code')
      ->add('region')
      ->add('name')
      ->add('published')
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
      ->add("code", TextType::class, [
        'label' => 'Код медицинской организации',
        'required' => true,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("name", TextType::class, [
        'required' => true,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("fullName", TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 512,
          ]),
        ],
      ])
      ->add("address", TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 512,
          ]),
        ],
      ])
      ->add('region')
      ->add('years')
      ->add('published')
      ->add('chiefMedicalOfficer',  AdminType::class);
  }

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('code')
      ->add('name')
      ->add('region')
      ->add('years')
      ->add('published');
  }
}