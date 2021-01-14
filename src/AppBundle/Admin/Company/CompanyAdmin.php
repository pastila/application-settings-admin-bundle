<?php

namespace AppBundle\Admin\Company;

use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
      ->add('id')
      ->add('name')
      ->add('moderationStatus', 'choice', [
        'label' => 'Статус модерации',
        'editable' => true,
        'choices' => FeedbackModerationStatus::getAvailableNames(),
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
      ->add('branch', EntityType::class, [
        'label' => 'Филиал СМО',
        'class' => CompanyBranch::class,
        'choice_label' => 'regionName',
      ])
      ->add('author', null, [
        'label' => 'Авторизованный пользователь',
        'help' => 'Можно оставить пустым, если пользватель не авторизованный',
      ])
      ->add('authorName', null, [
        'label' => 'Имя пользователя',
        'help' => 'Подставляется автоматически после сохранения, если пользователь выбран как авторизованный',
      ])
      ->add("title", TextType::class, [
        'required' => true,
        'constraints' => [
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("text", TextareaType::class, [
        'required' => true,
        'attr' => [
          'rows' => 5
        ],
        'constraints' => [
          new Length([
            'min' => 10,
          ]),
        ],
      ])
      ->add('valuation', 'AppBundle\Form\Feedback\FeedbackValuationChoiceType', [
        'label' => 'Оценка',
        'required' => false,
        'expanded' => false,
      ])
      ->add("createdAt", DatePickerType::class, [
        'format' => 'd MMMM yyyy',
        'view_timezone' => 'UTC',
        'model_timezone' => 'UTC',
      ])
      ->add('moderationStatus', 'AppBundle\Form\Feedback\FeedbackStatusChoiceType', [
        'required' => true,
        'label' => 'Статус модерации',
        'constraints' => [
          new NotBlank(),
        ],
      ]);
  }

  /**
   * @param RouteCollection $collection
   */
  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->remove('create');
  }
}