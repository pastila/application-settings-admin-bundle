<?php

namespace AppBundle\Admin\InsuranceCompany;

use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Form\DataTransformer\RegionToEntityTransformer;
use AppBundle\Repository\Geo\RegionRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sonata\Form\Type\CollectionType;

/**
 * Class InsuranceCompanyBranchAdmin
 * @package AppBundle\Admin\InsuranceCompany
 */
class InsuranceCompanyBranchAdmin extends AbstractAdmin
{
  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('region', TextType::class, [
        'label' => 'Регион',
        'attr' => array(
          'readonly' => true,
        ),
      ])

      ->add('representatives', CollectionType::class, [
        'by_reference' => false,
        'label' => 'Представители СМО:',
        'btn_add' => true,
        'type_options' => [
          'delete' => true,
//          'delete_options' => [
//            'type_options' => [
//              'mapped'   => false,
//              'required' => false,
//            ]
//          ]
        ]
      ], [
        'edit' => 'inline',
        'inline' => 'table',
        'allow_delete' => true,
        'allow_add'=>true,
//        'sortable' => 'name',
        'admin_code' => 'main.admin.insurance_representative'
      ])








      ->add('published')
      ->get('region')
        ->addModelTransformer(new RegionToEntityTransformer($this->getSubject()));
    ;
  }
}