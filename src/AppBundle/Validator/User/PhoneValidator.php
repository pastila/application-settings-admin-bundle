<?php

namespace AppBundle\Validator\User;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneValidator extends ConstraintValidator
{
  /**
   * @param string $value
   * @param Constraint|Phone $constraint
   */
  public function validate ($value, Constraint $constraint)
  {
    if (!preg_match('/^\+[0-9]{1,2}\s?\([0-9]{3}\)[0-9]+\-[0-9]+\-[0-9]+$/', $value))
    {
      $this->context->addViolation($constraint->message);
    }
  }

}