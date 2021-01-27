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
class ChiefMedicalOfficerAdmin extends AbstractAdmin
{
  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add("lastName", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("firstName", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
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
      ]);
  }
}