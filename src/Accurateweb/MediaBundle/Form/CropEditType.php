<?php


namespace Accurateweb\MediaBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CropEditType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('x', 'Symfony\Component\Form\Extension\Core\Type\NumberType',[
        'required' => true,
      ])
      ->add('x1', 'Symfony\Component\Form\Extension\Core\Type\NumberType',[
        'required' => true,
      ])
      ->add('y', 'Symfony\Component\Form\Extension\Core\Type\NumberType',[
        'required' => true,
      ])
      ->add('y1', 'Symfony\Component\Form\Extension\Core\Type\NumberType',[
        'required' => true,
      ])
      ->add('entity', 'Symfony\Component\Form\Extension\Core\Type\TextType',[
        'required' => true,
      ])
      ->add('id', 'Symfony\Component\Form\Extension\Core\Type\TextType',[
        'required' => true,
      ])
    ;
  }
}