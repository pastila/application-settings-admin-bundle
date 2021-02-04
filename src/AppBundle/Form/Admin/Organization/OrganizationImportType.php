<?php

namespace AppBundle\Form\Admin\Organization;

use AppBundle\Helper\Year\Year;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrganizationImportType extends AbstractType
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $years = Year::getChoicesYear();
    $builder
      ->add('year', ChoiceType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
        'choices' => $years,
        'data' => (count($years) - 1)
      ])
      ->add('file', 'Symfony\Component\Form\Extension\Core\Type\FileType', [
        'label' => 'Файл для импорта',
        'data' => null,
        'sonata_help' => 'Файл в формате xlsx',
        'allow_file_upload' => true,
        'constraints' => [
          new File([
            'mimeTypes' => [
              'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
              'application/vnd.ms-excel',
              'application/vnd.ms-office',
            ],
            'mimeTypesMessage' => 'Разрешена загрузка только в формате xlsx. Передан {{ type }}',
          ]),
        ]
      ])
      ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
        'label' => 'Загрузить',
        'attr' => [
          'class' => 'btn btn-success',
        ],
      ])
    ;
  }
}