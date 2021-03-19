<?php

namespace AppBundle\Form\Admin\Organization;

use AppBundle\Helper\Year\Year;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrganizationExportType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $years = Year::getChoicesYear();
    $builder
      ->add('year', ChoiceType::class, [
        'required' => false,
        'constraints' => [
          new NotBlank(),
        ],
        'choices' => $years,
      ])
      ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
        'label' => 'Скачать',
        'attr' => [
          'class' => 'btn btn-success',
        ],
      ])
    ;
  }
}