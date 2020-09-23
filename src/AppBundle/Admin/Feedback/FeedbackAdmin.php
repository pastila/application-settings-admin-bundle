<?php

namespace AppBundle\Admin\Feedback;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
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
  protected function configureListFields (ListMapper $list)
  {
    $list
      ->add('branch', null, [
        'label' => 'Отделение',
      ])
      ->add('region', null, [
        'label' => 'Регион',
      ])
      ->add('title', null, [
        'label' => 'Заголовок',
      ])
      ->add('user', null, [
        'label' => 'Пользователь',
      ])
      ->add('moderationStatusLabel', null, [
        'label' => 'Статус модерации',
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
  protected function configureFormFields (FormMapper $form)
  {
    $form
      ->add('moderationStatus', 'AppBundle\Form\Feedback\FeedbackStatusChoiceType',[
        'required' => true,
        'label' => 'Статус модерации',
        'constraints' => [
          new NotBlank(),
        ],
      ])
    ;
  }
}