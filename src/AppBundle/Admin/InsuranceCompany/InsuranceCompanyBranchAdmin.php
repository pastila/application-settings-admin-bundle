<?php

namespace AppBundle\Admin\InsuranceCompany;


use AppBundle\Entity\Company\FeedbackModerationStatus;
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
//  /**
//   * @param ListMapper $list
//   */
//  protected function configureListFields(ListMapper $list)
//  {
//    $list
//      ->add('name')
//      ->add('published', null, [
//        'label' => 'Публикация',
//      ])
//      ->add('_action', null, array(
//        'label' => 'Действия',
//        'actions' => array(
//          'edit' => array(),
//          'delete' => array(),
//        )
//      ));
//  }

  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
//      ->add("name", TextType::class, [
//        'required' => true,
//        'constraints' => [
//          new Length([
//            'min' => 3,
//            'max' => 255,
//          ]),
//        ],
//      ])
      ->add("region")
      ->add('published', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ]);
  }
}