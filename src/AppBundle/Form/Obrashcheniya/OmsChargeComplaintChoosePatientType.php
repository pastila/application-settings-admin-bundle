<?php

namespace AppBundle\Form\Obrashcheniya;

use AppBundle\Entity\User\Patient;
use AppBundle\Repository\User\PatientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OmsChargeComplaintChoosePatientType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $user = $options['user'];
    $builder
      ->add('patient', EntityType::class, [
        'expanded' => true,
        'required' => false,
        'placeholder' => 'Создать нового пациента',
        'class' => Patient::class,
        'query_builder' => function(PatientRepository $patientRepository) use ($user) {
          return $patientRepository
            ->createQueryBuilder('p')
            ->where('p.user = :user')
            ->setParameter('user', $user);
        },
      ]);
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
    $resolver->setRequired('user');
  }
}