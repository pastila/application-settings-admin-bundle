<?php

namespace AppBundle\Validator\Feedback;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class FeedbackAuthor
 * @package AppBundle\Validator\Feedback
 *
 * @Annotation
 */
class FeedbackAuthor extends Constraint
{
  public $message = '{message}';

  public function getTargets()
  {
    return self::PROPERTY_CONSTRAINT;
  }
}