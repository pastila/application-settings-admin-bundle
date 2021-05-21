<?php

namespace AppBundle\Form\Organization;

use AppBundle\Entity\Geo\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicalOrganizationFilterType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('query', TextType::class, [])
      ->add('region', EntityType::class, [
        'class' => Region::class,
      ])
      ->add('year', TextType::class, []);
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', 'AppBundle\Model\Filter\MedicalOrganizationFilter');
  }
}