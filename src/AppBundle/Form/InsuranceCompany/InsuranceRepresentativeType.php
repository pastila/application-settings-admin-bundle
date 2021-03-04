<?php

namespace AppBundle\Form\InsuranceCompany;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class InsuranceRepresentativeType
 * @package AppBundle\Form\InsuranceCompany
 */
class InsuranceRepresentativeType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('email', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Email(),
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add('lastName', null, [
        'constraints' => [
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add('firstName', null, [
        'constraints' => [
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add('middleName', null, [
        'constraints' => [
          new Length([
            'max' => 255,
          ]),
        ],
      ]);
    ;
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', 'AppBundle\Entity\Company\InsuranceRepresentative');
  }
}