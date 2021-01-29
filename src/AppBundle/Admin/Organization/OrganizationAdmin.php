<?php

namespace AppBundle\Admin\Organization;

use AppBundle\Entity\Organization\Organization;
use AppBundle\Form\DataTransformer\YearsToCollectionTransformer;
use AppBundle\Helper\Year\Year;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class OrganizationAdmin
 * @package AppBundle\Admin\Organization
 */
class OrganizationAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('code')
      ->add('region')
      ->add('name')
      ->add('published')
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
    $container = $this->getConfigurationPool()->getContainer();
    $em = $container->get('doctrine.orm.entity_manager');

    $form
      ->with('Медицинская организация')
      ->add("code", TextType::class, [
        'label' => 'Код медицинской организации',
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("name", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("fullName", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 3,
            'max' => 512,
          ]),
        ],
      ])
      ->add("address", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 3,
            'max' => 512,
          ]),
        ],
      ])
      ->add('region', null, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('years', ChoiceType::class, [
        'multiple' => true,
        'choices' => Year::getYears(),
        'label' => 'Года',
        'required' => false,
      ])
      ->add('published')
      ->end()
      ->with('Руководитель')
      ->add('chiefMedicalOfficer', AdminType::class, [
        'label' => 'Главный врач:',
      ], [
          'admin_code' => 'main.admin.organization_chief_medical_officer'
        ]
      )
      ->end()

      ->get('years')
      ->addModelTransformer(new YearsToCollectionTransformer($em, $this->getSubject()));
  }

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('code')
      ->add('region')
      ->add('name')
      ->add('years.year', null, ['label' => 'Год',],
        'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
          'choices' => Year::getYears(),
        ])
      ->add('published');
  }

  /**
   * @param $object
   */
  public function prePersist($object)
  {
    $this->setRelation($object);
  }

  /**
   * @param $object
   */
  public function preUpdate($object)
  {
    $this->setRelation($object);
  }

  /**
   * @param Organization $object
   */
  protected function setRelation($object)
  {
    $object->getChiefMedicalOfficer()->setOrganization($object);
  }
}