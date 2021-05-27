<?php

namespace AppBundle\Admin\OmsChargeComplaint;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OmsChargeComplaintAdmin extends AbstractAdmin
{
  protected $datagridValues = [
    '_sort_order' => 'DESC',
    '_sort_by' => 'createdAt',
  ];

  protected function configureListFields (ListMapper $list)
  {
    $list
      ->add('patient.user', null, [
        'label' => 'Пользователь',
      ])
      ->add('patient')
      ->add('year')
      ->add('region')
      ->add('medicalOrganization')
      ->add('statusLabel', null, [
        'label' => 'Статус',
      ])
      ->add('createdAt')
      ->add('_action', null, array(
        'actions' => array(
          'show' => array(),
          'edit' => array(),
          'delete' => array(),
        )
      ));
  }

  protected function configureFormFields (FormMapper $form)
  {
    $form
      ->add('region')
      ->add('medicalOrganization')
      ->add('urgent', ChoiceType::class, [
        'choices' => [
          'Плановое' => '0',
          'Неотложное' => '1',
        ],
      ])
      ->add('disease')
      ->add('patient')
      ->add('status', ChoiceType::class, [
        'choices' => [
          'Черновик' => OmsChargeComplaint::STATUS_DRAFT,
          'Создано' => OmsChargeComplaint::STATUS_CREATED,
          'Отправлено' => OmsChargeComplaint::STATUS_SENT,
        ],
      ])
      ->add('paidAt', DatePickerType::class, [
        'format' => 'd MMMM yyyy',
        'view_timezone' => 'UTC',
        'model_timezone' => 'UTC',
      ])
      ->add('sentAt', DatePickerType::class, [
        'format' => 'd MMMM yyyy',
        'view_timezone' => 'UTC',
        'model_timezone' => 'UTC',
      ]);
  }

  protected function configureShowFields (ShowMapper $show)
  {
    $show
      ->with('Обращение')
      ->add('status', 'choice', [
        'choices' => [
          OmsChargeComplaint::STATUS_DRAFT => 'Черновик',
          OmsChargeComplaint::STATUS_CREATED => 'Создано',
          OmsChargeComplaint::STATUS_SENT => 'Отправлено',
        ],
      ])
      ->add('sentAt')
      ->end()
      ->with('1 шаг')
      ->add('year')
      ->add('region')
      ->end()
      ->with('2 шаг')
      ->add('medicalOrganization')
      ->end()
      ->with('3 шаг')
      ->add('urgent', 'choice', [
        'choices' => [
          '0' => 'Плановое',
          '1' => 'Неотложное',
        ],
      ])
      ->end()
      ->with('4 шаг')
      ->add('disease')
      ->end()
      ->with('5 шаг')
      ->add('patient')
//      ->add('patientData.fio', null, [
//        'label' => 'Пациент',
//      ])
      ->add('patientData.phone', null, [
        'label' => 'Номер телефона',
      ])
      ->add('patientData.birthDate', null, [
        'label' => 'Дата рождения',
        'format' => 'd.m.Y',
      ])
      ->add('patientData.insurancePolicyNumber', null, [
        'label' => 'Номер полиса',
      ])
      ->add('patientData.insuranceCompany', null, [
        'label' => 'Страховая компания',
      ])
      ->add('patientData.region', null, [
        'label' => 'Регион',
      ])
      ->add('paidAt', 'datetime', [
        'format' => 'd.m.Y',
      ])
      ->add('documents', null, [
        'template' => '@App/admin/oms_charge_complaint/documents_show.html.twig',
      ])
      ->end()
    ;
  }

  protected function configureDatagridFilters (DatagridMapper $filter)
  {
    $filter
      ->add('region')
      ->add('medicalOrganization')
      ->add('urgent', null, [], 'choice', [
        'choices' => [
          'Плановое' => '0',
          'Неотложное' => '1',
        ],
      ])
      ->add('disease')
      ->add('patient')
      ->add('status', null, [], 'choice', [
        'choices' => [
          'Черновик' => OmsChargeComplaint::STATUS_DRAFT,
          'Создано' => OmsChargeComplaint::STATUS_CREATED,
          'Отправлено' => OmsChargeComplaint::STATUS_SENT,
        ],
      ])
    ;
  }

  protected function configureRoutes (RouteCollection $collection)
  {
    $collection->remove('create');
    $collection->remove('export');
  }
}