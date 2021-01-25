<?php

namespace AppBundle\Admin\Feedback;

use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Validator\Feedback\FeedbackAuthor;
use AppBundle\Validator\Feedback\FeedbackValidator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Sonata\CoreBundle\Validator\ErrorElement;

/**
 * Class FeedbackAdmin
 * @package AppBundle\Admin\Feedback
 */
class FeedbackAdmin extends AbstractAdmin
{
  /**
   * @var array
   */
  protected $datagridValues = array(
    '_page' => 1,
    '_sort_order' => 'ASC',
    '_sort_by' => 'createdAt',
  );

  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
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
      ->add('createdAt', 'date', [
        'label' => 'Дата публикации',
        'format' => 'Y-m-d H:i:s'
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
        'required' => true,
        'label' => 'Имя пользователя',
        'help' => 'Подставляется автоматически после сохранения, если пользователь выбран как авторизованный',
      ])
      ->add("title", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
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
          new NotBlank(),
          new Length([
            'min' => 10,
          ]),
        ],
      ])
      ->add('valuation', 'AppBundle\Form\Feedback\FeedbackValuationChoiceType', [
        'label' => 'Оценка',
        'required' => false,
        "expanded" => false
      ])
      ->add("createdAt", DateTimePickerType::class, [
        'required' => true,
        'by_reference' => true,
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

  /**
   * 
   */
  protected function attachInlineValidator ()
  {
    $metadata = $this->validator->getMetadataFor($this->getClass());
    $metadata->addConstraint(new FeedbackAuthor());

    return parent::attachInlineValidator();
  }
}