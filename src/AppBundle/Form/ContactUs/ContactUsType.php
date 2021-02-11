<?php

namespace AppBundle\Form\ContactUs;

use AppBundle\Util\EndingFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class ContactUsType extends AbstractType
{
  const MESSAGE_LENGTH_MIN = 10;
  const MESSAGE_LENGTH_MAX = 512;

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
          new Length([
            'max' => self::MESSAGE_LENGTH_MAX,
            'maxMessage' => 'Письмо слишком длинное. Пожалуйста, введите не более {{ limit }} ' .
              EndingFormatter::format(self::MESSAGE_LENGTH_MAX, ['символа', 'символов', 'символов']) .
              ' или меньше.',
            'min' => self::MESSAGE_LENGTH_MIN,
            'minMessage' => 'Письмо слишком короткое. Пожалуйста, введите более {{ limit }} ' .
              EndingFormatter::format(self::MESSAGE_LENGTH_MIN, ['символа', 'символов', 'символов']) .
              ' или больше.',
          ]),
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', 'AppBundle\Entity\ContactUs\ContactUs');
  }
}
