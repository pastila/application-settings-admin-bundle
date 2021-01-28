<?php

namespace AppBundle\Admin\Organization;

use AppBundle\Entity\Organization\Organization;
use AppBundle\Entity\Organization\OrganizationStatus;
use AppBundle\Form\Organization\OrganizationYearType;
use AppBundle\Helper\Year\Year;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Symfony\Component\Form\CallbackTransformer;
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
  /*
   *
[2020-12-21 11:56:02] console.ERROR: Error thrown while running command "rabbitmq:consumer obrashcheniya_emails --env=prod". Message: "Error Connecting to server(111): Connection refused " {"exception":"[object] (PhpAmqpLib\\Exception\\AMQPIOException(code: 111): Error Connecting to server(111): Connection refused  at /var/www/vendor/php-amqplib/php-amqplib/PhpAmqpLib/Wire/IO/StreamIO.php:114)","command":"rabbitmq:consumer obrashcheniya_emails --env=prod","message":"Error Connecting to server(111): Connection refused "} []
[2020-12-21 11:56:02] console.DEBUG: Command "rabbitmq:consumer obrashcheniya_emails --env=prod" exited with code "111" {"command":"rabbitmq:consumer obrashcheniya_emails --env=prod","code":111} []
[20
   * */


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
    $form
      ->with('Медицинская организация')
      ->add("code", TextType::class, [
        'label' => 'Код медицинской организации',
        'required' => true,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
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
      ->add("fullName", TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 512,
          ]),
        ],
      ])
      ->add("address", TextType::class, [
        'required' => false,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 512,
          ]),
        ],
      ])
      ->add('region')
      ->add('yearsRaw', OrganizationYearType::class, [
        'multiple' => true,
      ])
      ->add('published')
      ->end()
      ->with('Руководитель')
      ->add('chiefMedicalOfficer', AdminType::class, [
        'label' => 'Глав.врач:',
        'by_reference' => false
      ])
      ->end()
//
//    ->get('years')
//      ->addModelTransformer(new CallbackTransformer(
//        function ($tagsAsArray) {
//          if (is_array($tagsAsArray))
//          {
//            dump($tagsAsArray);die;
//          }
//          // transform the array to a string
//          return [];
//        },
//        function ($tagsAsString) {
//          dump($tagsAsString);die;
//          // transform the string back to an array
//          return explode(', ', $tagsAsString);
//        }
//      ))
    ;
  }

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('code')
      ->add('name')
      ->add('region')
      ->add('years')
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
    dump($object->getYears()->first());
    die;
    $object->getChiefMedicalOfficer()->setOrganization($object);
  }
}