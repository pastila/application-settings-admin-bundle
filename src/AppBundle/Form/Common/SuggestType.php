<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SuggestType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', []);
  }
}