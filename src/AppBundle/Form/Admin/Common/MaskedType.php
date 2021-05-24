<?php

namespace AppBundle\Form\Admin\Common;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaskedType extends TextType
{
  public function configureOptions (OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
    $resolver->setRequired('mask');
  }

  public function finishView (FormView $view, FormInterface $form, array $options)
  {
    parent::finishView($view, $form, $options);
    $view->vars['mask'] = $options['mask'];
  }

  public function getBlockPrefix ()
  {
    return 'masked';
  }
}