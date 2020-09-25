<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Widget;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezbahilAutocompleteCompanyType extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setRequired('choices');
  }

  public function buildView(FormView $view, FormInterface $form, array $options)
  {
    $view->vars['choices'] = $options['choices'];
  }

  public function getBlockPrefix()
  {
    return 'bezbahil_autocomplete_company';
  }
}
