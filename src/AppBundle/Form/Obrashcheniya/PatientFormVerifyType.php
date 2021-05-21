<?php

namespace AppBundle\Form\Obrashcheniya;

use AppBundle\Validator\User\Phone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PatientFormVerifyType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('phone', TextType::class, [
        'constraints' => [
          new Phone(),
        ],
      ]);
  }
}