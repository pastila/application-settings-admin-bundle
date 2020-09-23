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
      ->add('branch')
      ->add('title')
      ->add('user')
      ->add('moderationStatus');
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