<?php

namespace AppBundle\Form\Patient;

use AppBundle\Model\Filter\PatientFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientFilterType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('lastName', TextType::class)
      ->add('firstName', TextType::class)
      ->add('middleName', TextType::class)
      ->add('insurancePolicyNumber', TextType::class);
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', PatientFilter::class);
  }
}