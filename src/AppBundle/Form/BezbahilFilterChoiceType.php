<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezbahilFilterChoiceType extends AbstractType
{
  public function getBlockPrefix()
  {
    return 'bezbahil_filter_choice';
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver
      ->setRequired(['url_builder', 'clear_label']);
  }

  public function buildView (FormView $view, FormInterface $form, array $options)
  {
    $view->vars['url_builder'] = $options['url_builder'];
    $view->vars['clear_label'] = $options['clear_label'];
  }

  public function getParent()
  {
    return ChoiceType::class;
  }
}
