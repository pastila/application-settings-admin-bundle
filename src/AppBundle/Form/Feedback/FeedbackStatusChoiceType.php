<?php

namespace AppBundle\Form\Feedback;

use AppBundle\Entity\Company\FeedbackModerationStatus;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FeedbackStatusChoiceType
 * @package AppBundle\Form\Feedback
 */
class FeedbackStatusChoiceType extends ChoiceType
{
  public function configureOptions (OptionsResolver $resolver)
  {
    $choices = [
      'Одобрено' => FeedbackModerationStatus::MODERATION_ACCEPTED,
      'Отклонено' => FeedbackModerationStatus::MODERATION_REJECTED,
      'Требует модерации' => FeedbackModerationStatus::MODERATION_NONE,
    ];

    parent::configureOptions($resolver);
    $resolver->setDefault('choices', $choices);
  }
}