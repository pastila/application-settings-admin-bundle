<?php

namespace AppBundle\Form\ContactUs;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class ContactUsType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('author_name', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length(['min' => 3, 'max' => 255]),
        ]
      ])
      ->add('email', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length(['max' => 255]),
          new Email(),
        ],
      ])
      ->add('message', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length(['min' => 10, 'max' => 512]),
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', 'AppBundle\Entity\ContactUs\ContactUs');
  }
}
