<?php

namespace AppBundle\Form\Feedback;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('feedback')
      ->add('text', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 255,
          ]),
        ],
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', 'AppBundle\Entity\Company\Comment');
  }
}
