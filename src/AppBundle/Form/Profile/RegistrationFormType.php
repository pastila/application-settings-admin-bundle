<?php

namespace AppBundle\Form\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class RegistrationFormType
 * @package AppBundle\Form\Profile
 */
class RegistrationFormType extends AbstractType
{
  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('email', EmailType::class, [
      'required' => true,
      'label' => 'Электронная почта',
      'constraints' => [
        new Length([
          'max' => 255,
        ]),
      ]
    ]);
    $builder->add('plainPassword', RepeatedType::class, array(
      'label' => 'Пароль',
      'type' => PasswordType::class,
      'options' => array(
        'translation_domain' => 'FOSUserBundle',
        'attr' => array(
          'autocomplete' => 'new-password',
        ),
      ),
      'first_options' => array('label' => 'Пароль'),
      'second_options' => array('label' => 'Подтвердите пароль'),
      'invalid_message' => 'Пароли не совпадают!',
      'constraints' => array(
        new Length([
          'min' => 6,
        ]),
      ),
    ));
    $builder->add('username', HiddenType::class, [
      'error_bubbling' => false
    ]);
    $builder->add('termsAndConditionsAccepted', HiddenType::class);

    $builder->addEventListener(
      FormEvents::PRE_SUBMIT,
      [$this, 'onPreSubmit']
    );
  }

  /**
   * @param FormEvent $event
   */
  public function onPreSubmit(FormEvent $event): void
  {
    $user = $event->getData();
    if (!$user) {
      return;
    }
    $user['username'] = !empty($user['email']) ? $user['email'] : null;
    $user['termsAndConditionsAccepted'] = true;
    $event->setData($user);
  }

  /**
   * @return string|null
   */
  public function getParent()
  {
    return BaseRegistrationFormType::class;
  }

  /**
   * @return string
   */
  public function getBlockPrefix()
  {
    return 'app_user_registration';
  }
}
