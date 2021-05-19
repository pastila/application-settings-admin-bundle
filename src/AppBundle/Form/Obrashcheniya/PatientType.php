<?php


namespace AppBundle\Form\Obrashcheniya;


use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\Patient;
use AppBundle\Validator\User\Phone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PatientType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('lastName', TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(['message' => 'Укажите фамилию',]),
        ],
      ])
      ->add('firstName', TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(['message' => 'Укажите имя',]),
        ],
      ])
      ->add('middleName', TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(['message' => 'Укажите отчество',]),
        ],
      ])
      ->add('birthDate', DateType::class, [
        'required' => true,
        'widget' => 'single_text',
        'constraints' => [
          new NotBlank(['message' => 'Укажите дату рождения',]),
        ],
      ])
      ->add('insurancePolicyNumber', TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(['message' => 'Укажите номер полиса',]),
        ],
      ])
      ->add('region', EntityType::class, [
        'required' => true,
        'class' => Region::class,
        'constraints' => [
          new NotBlank(['message' => 'Укажите регион страховой компании',]),
        ],
      ])
      ->add('insuranceCompany', EntityType::class, [
        'required' => true,
        'class' => InsuranceCompany::class,
        'constraints' => [
          new NotBlank(['message' => 'Укажите страховую компанию',]),
        ],
      ])
      ->add('phone', TextType::class, [
        'required' => true,
        'constraints' => [
          new Phone(),
        ],
      ])
      ->add('verificationCode', TextType::class, [
        'required' => false,
      ])
    ;
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', Patient::class);
  }
}