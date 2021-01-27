<?php

namespace AppBundle\Validator\Feedback;

use AppBundle\Entity\Company\Feedback;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FeedbackAuthorValidator extends ConstraintValidator
{
  /**
   * @param Feedback $value
   * @param Constraint $constraint
   */
  public function validate($value, Constraint $constraint)
  {
    if (is_null($value))
    {
      return;
    }

    if (empty($value->getAuthor()) && empty($value->getAuthorName()))
    {
      $this->context->addViolation($constraint->message, [
        '{message}' => 'Необходимо выбрать пользователя!',
      ]);
    }
  }
}