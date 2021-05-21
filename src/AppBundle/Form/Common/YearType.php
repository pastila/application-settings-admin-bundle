<?php

namespace AppBundle\Form\Common;

use AppBundle\Helper\Year\Year;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/*
 * Селект года, по умолчанию выбран последний
 */
class YearType extends ChoiceType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preSetData']);
  }

  public function preSetData (FormEvent $event)
  {
    if ($event->getData() === null)
    {
      $choices = $event->getForm()->getConfig()->getOption('choices');
      $lastItem = end($choices);
      $event->setData($lastItem);
    }
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
    $years = Year::getChoicesYear();
    $resolver->setDefault('choices', $years);
  }
}