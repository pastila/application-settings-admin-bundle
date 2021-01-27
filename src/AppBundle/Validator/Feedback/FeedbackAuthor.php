<?php

namespace AppBundle\Validator\Feedback;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FeedbackAuthor extends Constraint
{
  public $message = '{message}';

  public function getTargets()
  {
    return self::CLASS_CONSTRAINT;
  }
}