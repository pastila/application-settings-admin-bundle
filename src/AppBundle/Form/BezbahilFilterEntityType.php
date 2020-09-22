<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezbahilFilterEntityType extends AbstractType
{
  public function getBlockPrefix()
  {
    return 'bezbahil_filter_choice';
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setRequired('url_builder');
  }

  public function buildView (FormView $view, FormInterface $form, array $options)
  {
    $view->vars['url_builder'] = $options['url_builder'];
  }

  public function getParent()
  {
    return EntityType::class;
  }
}
