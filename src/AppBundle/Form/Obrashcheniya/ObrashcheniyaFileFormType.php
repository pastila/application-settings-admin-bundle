<?php


namespace AppBundle\Form\Obrashcheniya;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObrashcheniyaFileFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('author', null, [
        'required' => true,
      ])
      ->add('type', null, [
        'required' => true,
      ])
      ->add('file', null, [
        'required' => true,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('data_class', 'AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile');
  }
}