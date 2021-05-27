<?php

namespace AppBundle\Admin\User;

use AppBundle\Entity\User\User;
use AppBundle\Form\Admin\Common\MaskedType;
use AppBundle\Validator\User\Phone;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class PatientAdmin extends AbstractAdmin
{
  public function createQuery ($context = 'list')
  {
    $query = parent::createQuery($context);

    if ($this->isChild())
    {
      /** @var User $user */
      $user = $this->getParent()->getSubject();

      $query->andWhere('o.user = :user');
      $query->setParameter('user', $user);
    }

    return $query;
  }

  protected function configureListFields (ListMapper $list)
  {
    $list
      ->add('fio')
      ->add('insurancePolicyNumber')
      ->add('phone')
      ->add('_action', null, array(
        'label' => 'Действия',
        'actions' => array(
          'edit' => array(),
//          'delete' => array(), //Нельзя удалять пациента с обращениями. А это пока единственный способ создания пациента
        )
      ));
  }

  protected function configureFormFields (FormMapper $form)
  {
    $form
      ->add('lastName', null, [
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('firstName', null, [
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('middleName', null, [
        'required' => false,
      ])
      ->add('insuranceCompany', null, [
        'constraints' => [
          new NotNull(),
        ],
      ])
      ->add('insurancePolicyNumber', null, [
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('phone', MaskedType::class, [
        'required' => false,
        'constraints' => [
          new Phone(),
        ],
        'mask' => '+7(999)999-99-99',
      ])
      ->add('birthDate', DatePickerType::class, [
        'format' => 'd MMMM yyyy',
        'view_timezone' => 'UTC',
        'model_timezone' => 'UTC',
      ])
      ->add('region');
  }

  protected function configureRoutes (RouteCollection $collection)
  {
    parent::configureRoutes($collection);
    //Пока что не делаем возможность создавать пациента в админке
    $collection->remove('create');
  }
}