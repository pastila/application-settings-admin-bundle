<?php

namespace AppBundle\Admin\Organization;

use AppBundle\Entity\Organization\OrganizationStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
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
      ->add('id')
      ->add('name')
      ->add('status', 'choice', [
        'label' => 'Статус модерации',
        'editable' => true,
        'choices' => OrganizationStatus::getAvailableName(),
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
      ->add("name", TextType::class, [
        'required' => true,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("nameFull", TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 512,
          ]),
        ],
      ])
      ->add("code", TextType::class, [
        'required' => true,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
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
      ->add("lastName", TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("firstName", TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("middleName", TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add('region', [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('status', 'AppBundle\Form\Organization\OrganizationStatusChoiceType', [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ]);
  }

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('name')
      ->add('region')
      ->add('status', null, [], 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
        'choices' => array_flip(OrganizationStatus::getAvailableName()),
      ]);
  }
}