<?php

namespace AppBundle\Admin\Feedback;

use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Form\Widget\BezbahilAutocompleteCompanyType;
use AppBundle\Model\Esim\EsimStatus;
use AppBundle\Repository\Company\CompanyBranchRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Templating\TemplateRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class FeedbackAdmin
 * @package AppBundle\Admin\Feedback
 */
class FeedbackAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('id', null, [
        'label' => 'ID',
      ])
      ->add('branch', null, [
        'label' => 'СМО',
      ])
      ->add('branch.region', null, [
        'label' => 'Регион',
      ])
      ->add('title', null, [
        'label' => 'Заголовок',
      ])
      ->add('author', null, [
        'label' => 'Пользователь',
      ])
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

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('title')
      ->add('author')
      ->add('author.email')
      ->add('branch.region')
      ->add('branch.company')
      ->add('moderationStatus', null, [], 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
        'choices' => array_flip(FeedbackModerationStatus::getAvailableNames()),
      ]);
  }
}