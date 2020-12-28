<?php

namespace AppBundle\Form\Feedback;

use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Company\FeedbackValuation;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FeedbackStatusChoiceType
 * @package AppBundle\Form\Feedback
 */
class FeedbackValuationChoiceType extends ChoiceType
{
  public function configureOptions (OptionsResolver $resolver)
  {
    $choices = FeedbackValuation::getAvailableValues();

    parent::configureOptions($resolver);
    $resolver->setDefault('choices', $choices);
  }
}