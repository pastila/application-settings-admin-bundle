<?php

namespace AppBundle\Admin\Feedback;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class CommentAdmin
 * @package AppBundle\Admin\Feedback
 */
class CommentAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields (ListMapper $list)
  {
    $list
      ->add('feedback', null, [
        'label' => 'Отзыв',
      ])
      ->add('text', null, [
        'label' => 'Текст',
      ])
      ->add('user', null, [
        'label' => 'Пользователь',
      ])
      ->add('moderationStatus', null, [
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
      ->add('moderationStatus', null, [
        'label' => 'Статус модерации',
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ]);
  }
}