<?php

namespace AppBundle\Admin\Company;


use AppBundle\Entity\Company\FeedbackModerationStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CompanyAdmin
 * @package AppBundle\Admin\Company
 */
class CompanyAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('kpp')
      ->add('name')
      ->add('published', null, [
        'label' => 'Публикация',
      ])
      ->add('_action', null, array(
        'label' => 'Действия',
        'actions' => array(
          'edit' => array(),
          'delete' => array(),
        )
      ));
  }

  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('logo', 'Accurateweb\MediaBundle\Form\ImageType', [
        'required' => false,
        'label' => 'Логотип',
      ])
      ->add("name", TextType::class, [
        'required' => true,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("kpp", TextType::class, [
        'required' => true,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add('published', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ]);
  }

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('name')
      ->add('published');
  }
}